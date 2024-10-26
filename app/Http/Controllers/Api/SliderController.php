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
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:2048',
            'image2' => 'nullable|file|image|max:2048',
            'clickable' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('slider-images', 'public');
        }

        if ($request->hasFile('image2')) {
            $validated['image2'] = $request->file('image2')->store('slider-images', 'public');
        }

        $slider = Slider::create($validated);
        return response()->json($slider, 201);
    }

    public function show(string $slug)
    {
        $slider = Slider::where('slug', $slug)->firstOrFail();
        return response()->json($slider);
    }

    public function update(Request $request, string $slug)
    {
        $slider = Slider::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:2048',
            'image2' => 'nullable|file|image|max:2048',
            'clickable' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image')->store('slider-images', 'public');
        }

        if ($request->hasFile('image2')) {
            if ($slider->image2) {
                Storage::disk('public')->delete($slider->image2);
            }
            $validated['image2'] = $request->file('image2')->store('slider-images', 'public');
        }

        $slider->update($validated);
        return response()->json($slider);
    }

    public function destroy(string $slug)
    {
        $slider = Slider::where('slug', $slug)->firstOrFail();

        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        if ($slider->image2) {
            Storage::disk('public')->delete($slider->image2);
        }

        $slider->delete();
        return response()->json(null, 204);
    }
}
