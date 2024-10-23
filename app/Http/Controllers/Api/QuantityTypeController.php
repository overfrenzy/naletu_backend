<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuantityType;
use Illuminate\Http\Request;

class QuantityTypeController extends Controller
{
    public function index()
    {
        $quantityTypes = QuantityType::all();
        return response()->json($quantityTypes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $quantityType = QuantityType::create($validated);

        return response()->json($quantityType, 201);
    }

    public function show(string $id)
    {
        $quantityType = QuantityType::findOrFail($id);
        return response()->json($quantityType);
    }

    public function update(Request $request, string $id)
    {
        $quantityType = QuantityType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $quantityType->update($validated);

        return response()->json($quantityType);
    }

    public function destroy(string $id)
    {
        $quantityType = QuantityType::findOrFail($id);

        // Check if the quantity type is associated with any products
        $productsCount = $quantityType->products()->count();

        if ($productsCount > 0) {
            // Prevent deletion
            return response()->json([
                'message' => 'Cannot delete quantity type because it is associated with products.'
            ], 400);
        } else {
            $quantityType->delete();
        }

        return response()->json(null, 204);
    }
}
