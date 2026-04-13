<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    protected function ensureMidtransConfig(): void
    {
        if (empty(config('midtrans.server_key')) || empty(config('midtrans.client_key'))) {
            throw new \RuntimeException('Midtrans credentials belum diset. Pastikan MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di file .env.');
        }
    }

    /**
     * Generate Midtrans Snap Token untuk Transfer Bank (VA)
     * Dipanggil saat kasir memilih metode Transfer di halaman POS.
     */
    public function createTransfer(Request $request): JsonResponse
    {
        $this->ensureMidtransConfig();

        $request->validate([
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required',
            'items.*.name'  => 'required|string',
            'items.*.price' => 'required|integer|min:0',
            'items.*.qty'   => 'required|integer|min:1',
            'total'         => 'required|integer|min:1',
        ]);

        $orderId = 'TRX-' . strtoupper(Str::random(10)) . '-' . time();

        $itemDetails = collect($request->items)->map(function ($item) {
            return [
                'id'       => (string) $item['id'],
                'price'    => (int) $item['price'],
                'quantity' => (int) $item['qty'],
                'name'     => substr($item['name'], 0, 50),
            ];
        })->toArray();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $request->total,
            ],
            'item_details' => $itemDetails,
            'enabled_payments' => [
                'bca_va', 'bni_va', 'bri_va', 'mandiri_bill',
                'permata_va', 'other_va',
            ],
            'callbacks' => [
                'finish' => url('/pos'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate QRIS langsung via Midtrans Core API
     * Dipanggil saat kasir memilih metode QRIS di halaman POS.
     */
    public function createQris(Request $request): JsonResponse
    {
        $this->ensureMidtransConfig();

        $request->validate([
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required',
            'items.*.name'  => 'required|string',
            'items.*.price' => 'required|integer|min:0',
            'items.*.qty'   => 'required|integer|min:1',
            'total'         => 'required|integer|min:1',
        ]);

        $orderId = 'QRIS-' . strtoupper(Str::random(10)) . '-' . time();

        $itemDetails = collect($request->items)->map(function ($item) {
            return [
                'id'       => (string) $item['id'],
                'price'    => (int) $item['price'],
                'quantity' => (int) $item['qty'],
                'name'     => substr($item['name'], 0, 50),
            ];
        })->toArray();

        $itemTotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $itemDetails));

        if ($itemTotal !== (int) $request->total) {
            $itemDetails = [[
                'id'       => 'QRIS-' . $orderId,
                'price'    => (int) $request->total,
                'quantity' => 1,
                'name'     => 'Pembayaran QRIS',
            ]];
        }

        $customerDetails = [
            'first_name' => 'Tamu',
            'email'      => 'guest@poscok.local',
            'phone'      => '+628000000000',
        ];

        if ($user = $request->user()) {
            $customerDetails = [
                'first_name' => $user->name ?? 'Tamu',
                'email'      => $user->email ?? 'guest@poscok.local',
                'phone'      => $user->phone ?? '+628000000000',
            ];
        }

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $request->total,
            ],
            'item_details'      => $itemDetails,
            'customer_details'  => $customerDetails,
            'qris' => [
                'acquirer' => 'gopay',
            ],
        ];

        try {
            /** @var object $response */
            $response = CoreApi::charge($params);

            // Ambil URL gambar QRIS dari response Midtrans
            $qrImageUrl = null;
            if (isset($response->actions)) {
                foreach ($response->actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $qrImageUrl = $action->url;
                        break;
                    }
                }
            }

            return response()->json([
                'success'      => true,
                'order_id'     => $orderId,
                'qr_image_url' => $qrImageUrl,
                'qr_string'    => $response->qr_string ?? null,
                'expire_time'  => $response->expiry_time ?? null,
                'status'       => $response->transaction_status ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat QRIS', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
                'params'   => $params,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QRIS: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cek status pembayaran (polling untuk QRIS & Transfer)
     */
    public function checkStatus(Request $request, string $orderId): JsonResponse
    {
        try {
            /** @var object $status */
            $status = \Midtrans\Transaction::status($orderId);

            $paid = in_array($status->transaction_status, ['settlement', 'capture']);

            return response()->json([
                'success'      => true,
                'order_id'     => $orderId,
                'status'       => $status->transaction_status,
                'paid'         => $paid,
                'payment_type' => $status->payment_type ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook Midtrans (notification handler)
     * Midtrans akan POST ke sini setelah pembayaran berhasil/gagal.
     */
    public function notification(Request $request): JsonResponse
    {
        try {
            /** @var object $notification */
            $notification = new \Midtrans\Notification();

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status ?? null;

            $isPaid = ($transactionStatus === 'settlement') ||
                      ($transactionStatus === 'capture' && $fraudStatus === 'accept');

            if ($isPaid) {
                // Update status transaksi di DB
                Transaction::where('midtrans_order_id', $orderId)
                    ->update(['payment_status' => 'paid']);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                Transaction::where('midtrans_order_id', $orderId)
                    ->update(['payment_status' => 'failed']);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}