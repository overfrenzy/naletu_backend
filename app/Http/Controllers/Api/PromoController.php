<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();
        return response()->json($promos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:promos,name',
            'discount' => 'required|integer|min:1|max:100',
            'product_id' => 'nullable|exists:products,id',
            'cart_total' => 'nullable|numeric',
        ]);

        $promo = Promo::create($validated);

        return response()->json($promo, 201);
    }

    public function show(string $id)
    {
        $promo = Promo::findOrFail($id);
        return response()->json($promo);
    }

    public function update(Request $request, string $id)
    {
        $promo = Promo::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:promos,name,' . $id,
            'discount' => 'required|integer|min:1|max:100',
            'product_id' => 'nullable|exists:products,id',
            'cart_total' => 'nullable|numeric',
        ]);

        $promo->update($validated);

        return response()->json($promo);
    }

    public function destroy(string $id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        return response()->json(null, 204);
    }
}
