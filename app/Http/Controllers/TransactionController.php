<?php
namespace App\Http\Controllers;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
class TransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('POS/Index', [
            'products' => Product::with('category')->where('aktif', true)->where('stok', '>', 0)->get(),
            'members'  => Member::all(),
        ]);
    }
    public function store(Request $request)
{
    $request->validate([
        'items'              => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.qty'        => 'required|integer|min:1',
        'bayar'              => 'required|numeric|min:0',
        'metode_bayar'       => 'required|in:tunai,transfer,qris',
        'redeem_poin'        => 'nullable|integer|min:0',
    ]);

    $trx = null;

    DB::transaction(function () use ($request, &$trx) {
        $subtotal = 0;
        $items    = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->stok < $item['qty']) {
                abort(422, "Stok {$product->nama} tidak cukup!");
            }
            $sub       = $product->harga_jual * $item['qty'];
            $subtotal += $sub;
            $items[]   = [
                'product_id'   => $product->id,
                'qty'          => $item['qty'],
                'harga_satuan' => $product->harga_jual,
                'subtotal'     => $sub,
            ];
            $product->decrement('stok', $item['qty']);
        }

        // Hitung diskon dari redeem poin
        $member       = $request->member_id ? \App\Models\Member::find($request->member_id) : null;
        $redeemPoin   = 0;
        $diskonRedeem = 0;

        if ($member && $request->redeem_poin > 0) {
            $redeemPoin   = min($request->redeem_poin, $member->poin);
            $diskonRedeem = $redeemPoin * config('poin.nilai_per_poin');
        }

        $diskon  = ($request->diskon ?? 0) + $diskonRedeem;
        $total   = max(0, $subtotal - $diskon);
        $kembali = $request->bayar - $total;

        $trx = Transaction::create([
            'user_id'      => Auth::id(),
            'member_id'    => $request->member_id ?: null,
            'subtotal'     => $subtotal,
            'diskon'       => $diskon,
            'total'        => $total,
            'bayar'        => $request->bayar,
            'kembali'      => $kembali,
            'metode_bayar' => $request->metode_bayar,
        ]);

        foreach ($items as $item) {
            $item['transaction_id'] = $trx->id;
            TransactionItem::create($item);
        }

        // Proses poin member
        if ($member) {
            $poinBefore = $member->poin;

            // Kurangi poin yang diredeem
            if ($redeemPoin > 0) {
                \App\Models\PointHistory::create([
                    'member_id'      => $member->id,
                    'transaction_id' => $trx->id,
                    'type'           => 'redeem',
                    'poin'           => $redeemPoin,
                    'poin_before'    => $poinBefore,
                    'poin_after'     => $poinBefore - $redeemPoin,
                    'keterangan'     => 'Redeem poin untuk diskon transaksi ' . $trx->no_transaksi,
                ]);
                $member->decrement('poin', $redeemPoin);
                $member->refresh();
            }

            // Tambah poin dari transaksi (earn)
            $poinEarned = (int) floor($total / config('poin.rupiah_per_poin'));
            if ($poinEarned > 0) {
                $poinBeforeEarn = $member->poin;
                \App\Models\PointHistory::create([
                    'member_id'      => $member->id,
                    'transaction_id' => $trx->id,
                    'type'           => 'earn',
                    'poin'           => $poinEarned,
                    'poin_before'    => $poinBeforeEarn,
                    'poin_after'     => $poinBeforeEarn + $poinEarned,
                    'keterangan'     => 'Poin dari transaksi ' . $trx->no_transaksi,
                ]);
                $member->increment('poin', $poinEarned);
            }
        }
    });

if ($trx) {
    $trx->load(['user', 'member', 'items.product']);
}

    return back()->with([
        'success'     => 'Transaksi berhasil!',
        'transaction' => $trx,
    ]);
}
    public function history()
    {
        $transactions = Transaction::with(['user', 'member', 'items.product'])
            ->latest()
            ->paginate(15);
        return Inertia::render('POS/History', [
            'transactions' => $transactions,
        ]);
    }
    public function show(Transaction $transaction)
    {
        return Inertia::render('POS/Receipt', [
            'transaction' => $transaction->load(['user', 'member', 'items.product']),
        ]);
    }
}