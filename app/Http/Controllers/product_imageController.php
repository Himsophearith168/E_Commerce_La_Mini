<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\product_images;

class product_imageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = product_images::all();

        $products->transform(function ($product) {
            $product->image_url = $product->image_path
                ? asset('storage/' . $product->image_path)
                : null;

            return $product;
        });

        return response()->json([
            'data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('products', 'public');
        }

        $productImage = product_images::create([
            'product_id' => $request->product_id,
            'image_path' => $imagePath,
        ]);
        $productImage->image_url = asset('storage/' . $productImage->image_path);

        return response()->json([
            'message' => 'Product image uploaded successfully',
            'data' => $productImage,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productImage = product_images::findOrFail($id);
        $productImage->image_url = $productImage->image_path
            ? asset('storage/' . $productImage->image_path)
            : null;

        return response()->json([
            'data' => $productImage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,id',
            'image' => 'sometimes|required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $productImage = product_images::findOrFail($id);
        $oldImagePath = null;

        if (array_key_exists('product_id', $validated)) {
            $productImage->product_id = $validated['product_id'];
        }

        if ($request->hasFile('image')) {
            $oldImagePath = $productImage->image_path;
            $productImage->image_path = $request->file('image')
                ->store('products', 'public');
        }

        $productImage->save();

        if ($oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        $productImage->image_url = asset('storage/' . $productImage->image_path);

        return response()->json([
            'message' => 'Product image updated successfully',
            'data' => $productImage,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productImage = product_images::findOrFail($id);
        $imagePath = $productImage->image_path;

        $productImage->delete();

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return response()->json([
            'message' => 'Product image deleted successfully',
        ]);
    }
}
