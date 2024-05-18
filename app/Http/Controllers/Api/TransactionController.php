<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transaction = Transaction::where('user_id', $request->user()->id)->get();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Get list transaction success',
            'data' => $transaction,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required',
        ]);

        // Get carts data
        $carts = Cart::with(['product'])->where('user_id', $request->user()->id)->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Keranjang belanja Anda kosong. Tidak dapat melakukan transaksi.'], 400);
        }

        // Hitung total harga produk
        $totalPrice = $carts->sum('product.price');

        // Tambahkan biaya pengiriman
        $shippingCost = 20000; // Misalnya, biaya pengiriman sebesar Rp 10.000
        $totalPrice += $shippingCost;

        // Create transaction
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'address_id' => $request->address_id,
            'trx_number' => 'TRX-' . time(),
            'total_price' => $totalPrice,
            'shipping_cost' => $shippingCost,
            'status' => 'pending',
        ]);

        foreach ($carts as $cart) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'user_id' => $cart->user_id,
                'product_id' => $cart->product_id,
            ]);
        }

        // Delete cart after transaction
        Cart::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Transaction success',
            'data' => $transaction
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with(['address', 'transactionItems.product'])->find($id);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Get transaction by id success',
            'data' => $transaction
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
