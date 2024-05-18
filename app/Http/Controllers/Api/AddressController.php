<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $address = Address::where('user_id', $request->user()->id)
            ->orderByDesc('is_default')// Sort by is_default field, true first
            ->get();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Get list address success',
            'data' => $address,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'full_address' => 'required',
        ]);

        $address = Address::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'full_address' => $request->full_address,
        ]);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Address created success',
            'data' => $address,
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
        // Clear all other default addresses
        Address::where('user_id', $request->user()->id)->update(['is_default' => false]);

        // Set the new default address
        Address::where('id', $id)->where('user_id', $request->user()->id)->update(['is_default' => true]);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Address updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
