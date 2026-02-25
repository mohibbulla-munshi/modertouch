<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Slider, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create() { return view('admin.sliders.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:200',
            'subtitle'    => 'nullable|string|max:300',
            'image'       => 'required|image|max:3072',
            'button_text' => 'nullable|string|max:80',
            'button_url'  => 'nullable|url',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
        ]);
        $data['image']     = $request->file('image')->store('sliders', 'public');
        $data['is_active'] = $request->boolean('is_active', true);
        Slider::create($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider added.');
    }

    public function edit(Slider $slider) { return view('admin.sliders.edit', compact('slider')); }

    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:200',
            'subtitle'    => 'nullable|string|max:300',
            'image'       => 'nullable|image|max:3072',
            'button_text' => 'nullable|string|max:80',
            'button_url'  => 'nullable|url',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slider->image);
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $slider->update($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated.');
    }

    public function destroy(Slider $slider)
    {
        Storage::disk('public')->delete($slider->image);
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Slider::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
