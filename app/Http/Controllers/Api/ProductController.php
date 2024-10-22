<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        // Учитывать отношения категории и количества.
        $query = Product::with(['category', 'quantityType']);

        // Фильтровать по Category_id, если он указан.
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Фильтровать по slug, если он указан
        if ($request->has('category_slug')) {
            $categorySlug = $request->input('category_slug');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Применить сортировку на основе параметра sort_by
        if ($request->has('sort_by')) {
            $sortBy = $request->input('sort_by');
            if (in_array($sortBy, ['mrp', 'selling_price'])) {
                $query->orderBy($sortBy, $request->input('order', 'asc'));
            }
        }

        // Разбивка на страницы или возврат всех продуктов на основе параметра per_page.
        $products = $request->has('per_page') ? $query->paginate($request->input('per_page')) : $query->get();

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
