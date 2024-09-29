<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with(['category', 'quantityType'])->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'mrp' => 'required|numeric',
            'selling_price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'quantity_type_id' => 'required|exists:quantity_types,id',
            'image' => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product-images', 'public');
            $validated['image'] = $imagePath;
        }

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function show(string $id)
    {
        $product = Product::with(['category', 'quantityType'])->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'mrp' => 'required|numeric',
            'selling_price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'quantity_type_id' => 'required|exists:quantity_types,id',
            'image' => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('product-images', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return response()->json($product);
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
