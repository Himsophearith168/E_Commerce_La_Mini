<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = products::all();
        return response()->json([
            'message' => 'Products retrieved successfully',
            'data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datavalidated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = products::create($datavalidated);
        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productCheckID = products::find($id);
        if (!$productCheckID) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => $productCheckID
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $productCheckID = products::find($id);
        if (!$productCheckID) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $datavalidated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        $productCheckID->update($datavalidated);
        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $productCheckID
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $idCheck = products::find($id);
        if (!$idCheck) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);    

        }
        $idCheck->delete();
        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
