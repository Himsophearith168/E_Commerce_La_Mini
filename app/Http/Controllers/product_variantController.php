<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product_variants;

class product_variantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = product_variants::all();
        return response()->json([
            'message' => 'You have successfully retrieved the data',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataValidated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);
        $data = product_variants::create($dataValidated);
        return response()->json([
            'message' => 'You have successfully created the data',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = product_variants::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }
        return response()->json([
            'message' => 'You have successfully retrieved the data',
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = product_variants::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }
        $dataValidated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,id',
            'variant_name' => 'sometimes|required|string|max:255',
            'sku' => 'sometimes|required|string|max:255|unique:product_variants,sku,' . $id,
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
        ]);
        $data->update($dataValidated);
        return response()->json([
            'message' => 'You have successfully updated the data',
            'data' => $data
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = product_variants::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }
        $data->delete();
        return response()->json([
            'message' => 'You have successfully deleted the data'
        ]);
    }
}
