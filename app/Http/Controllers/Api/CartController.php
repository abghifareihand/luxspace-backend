<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carts = Cart::with(['product.galleries'])->where('user_id', $request->user()->id)->get();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Get list carts success',
            'data' => $carts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Cek apakah item sudah ada di keranjang
        $existingCart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        // Jika sudah ada maka muncul pesan
        if ($existingCart) {
            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Product already in cart',
                'data' => $existingCart
            ]);
        }

        // Jika belum ada akan masuk
        $cart = Cart::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Add to Cart success',
            'data' => $cart
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $cart = Cart::find($id);
        $cart->delete();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Remove from Cart success',
            'data' => $cart
        ]);
    }
}
