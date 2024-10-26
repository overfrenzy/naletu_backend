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

    public function validatePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Преобразовать входные промокоды и промокоды базы данных в нижний регистр для соответствия без учета регистра.
        $promo = Promo::whereRaw('LOWER(name) = ?', [strtolower($request->code)])
            ->with('product.quantityType')
            ->first();

        if (!$promo) {
            return response()->json(['message' => 'Promo code is expired or invalid'], 404);
        }

        return response()->json([
            'discount' => $promo->discount,
            'product' => $promo->product,
            'cart_total' => $promo->cart_total,
        ]);
    }
}
