<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        return response()->json($sliders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:home,banner',
            'image' => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('slider-images', 'public');
            $validated['image'] = $imagePath;
        }

        $slider = Slider::create($validated);

        return response()->json($slider, 201);
    }

    public function show(string $id)
    {
        $slider = Slider::findOrFail($id);
        return response()->json($slider);
    }

    public function update(Request $request, string $id)
    {
        $slider = Slider::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:home,banner',
            'image' => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }

            $imagePath = $request->file('image')->store('slider-images', 'public');
            $validated['image'] = $imagePath;
        }

        $slider->update($validated);

        return response()->json($slider);
    }

    public function destroy(string $id)
    {
        $slider = Slider::findOrFail($id);
        
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return response()->json(null, 204);
    }
}
