<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasTokoScope;
use App\Models\Member;
use App\Models\Product;
use App\Models\PointHistory;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransactionController extends Controller
{
    use HasTokoScope;

    public function index()
    {
        session(['pageTitle' => 'Point of Sale', 'pageSubtitle' => '']);

        $products = $this->scopedQuery(Product::query())
            ->where('aktif', true)
            ->where('stok', '>', 0)
            ->with('category')
            ->get();

        $members = $this->scopedQuery(Member::query())
            ->where('status', true)
            ->get(['id', 'nama', 'telepon', 'poin']);

        return Inertia::render('POS/Index', [
            'products' => $products,
            'members'  => $members,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.harga'      => 'required|integer|min:0',
            'member_id'          => 'nullable|exists:members,id',
            'diskon'             => 'nullable|integer|min:0',
            'bayar'              => 'required|integer|min:0',
            'metode_bayar'       => 'required|in:tunai,transfer,qris',
            'payment_status'     => 'nullable|string',
        ]);

        $tokoId = $this->tokoId();

        $productIds = collect($request->items)->pluck('product_id');
        $invalidProduct = Product::whereIn('id', $productIds)
            ->where('toko_id', '!=', $tokoId)
            ->exists();
        abort_if($invalidProduct, 403, 'Produk tidak valid untuk toko ini.');

        if ($request->member_id) {
            $member = Member::find($request->member_id);
            abort_if($member->toko_id !== $tokoId, 403, 'Member tidak valid untuk toko ini.');
        }

        $transaction = DB::transaction(function () use ($request, $tokoId) {
            $subtotal = collect($request->items)->sum(fn($i) => $i['harga'] * $i['qty']);
            $diskon   = $request->diskon ?? 0;
            $total    = $subtotal - $diskon;
            $kembali  = $request->bayar - $total;

            $transaction = Transaction::create([
                'toko_id'        => $tokoId,
                'no_transaksi'   => 'TRX-' . strtoupper(uniqid()),
                'user_id'        => auth()->id(),
                'member_id'      => $request->member_id,
                'subtotal'       => $subtotal,
                'diskon'         => $diskon,
                'total'          => $total,
                'bayar'          => $request->bayar,
                'kembali'        => max(0, $kembali),
                'metode_bayar'   => $request->metode_bayar,
                'payment_method' => $request->metode_bayar,
                'payment_status' => $request->payment_status ?? 'paid',
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'qty'            => $item['qty'],
                    'harga_satuan'   => $item['harga'],
                    'subtotal'       => $item['harga'] * $item['qty'],
                ]);

                $product->decrement('stok', $item['qty']);

                StockMutation::create([
                    'toko_id'      => $tokoId,
                    'product_id'   => $product->id,
                    'user_id'      => auth()->id(),
                    'type'         => 'out',
                    'qty'          => $item['qty'],
                    'stok_before'  => $product->stok + $item['qty'], // stok sebelum dikurangi
                    'stok_after'   => $product->stok,               // stok sesudah dikurangi
                    'keterangan'   => 'Penjualan #' . $transaction->no_transaksi,
                ]);
            }

            if ($request->member_id) {
                $member   = Member::find($request->member_id);
                $poinBaru = (int) floor($total / config('poin.rupiah_per_poin', 10000));

                if ($poinBaru > 0) {
                    $poinBefore = $member->poin;
                    $member->increment('poin', $poinBaru);

                    PointHistory::create([
                        'member_id'      => $member->id,
                        'transaction_id' => $transaction->id,
                        'type'           => 'in',
                        'poin'           => $poinBaru,
                        'poin_before'    => $poinBefore,
                        'poin_after'     => $poinBefore + $poinBaru,
                        'keterangan'     => 'Poin dari transaksi #' . $transaction->no_transaksi,
                    ]);
                }
            }

            return $transaction;
        });

        $transaction->load(['items.product', 'member', 'user']);
        return redirect()->route('pos.index')
            ->with('transaction', $transaction)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    // Receipt / struk setelah transaksi
    public function show(Transaction $transaction)
    {
        abort_if($transaction->toko_id !== $this->tokoId(), 403);

        $transaction->load(['items.product', 'member', 'user', 'user.store']);

        return Inertia::render('POS/Receipt', [
            'transaction' => $transaction,
        ]);
    }

    // Riwayat transaksi (paginated + search)
    public function history(Request $request)
    {
        session(['pageTitle' => 'Riwayat Transaksi', 'pageSubtitle' => '']);

        $query = $this->scopedQuery(Transaction::query())
            ->with(['member', 'user', 'items'])
            ->latest();

        if ($request->filled('search')) {
            $query->where('no_transaksi', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('metode')) {
            $query->where('metode_bayar', $request->metode);
        }

        $transactions = $query->paginate(15)->withQueryString();

        return Inertia::render('POS/History', [
            'transactions' => $transactions,
        ]);
    }
}