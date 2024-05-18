<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Get products success',
            'data' => $products
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = Product::with('galleries')->find($id);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Get product by id success',
            'data' => $products
        ]);
    }

}
