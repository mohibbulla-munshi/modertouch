<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Category, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->withCount('products')
            ->orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->where('is_active', true)->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:150',
            'parent_id'        => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
            'sort_order'       => 'nullable|integer',
            'is_active'        => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $data['is_active'] = $request->boolean('is_active', true);

        $category = Category::create($data);
        ActivityLog::record('Created category: ' . $category->name, $category);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->where('is_active', true)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:150',
            'parent_id'        => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
            'sort_order'       => 'nullable|integer',
            'is_active'        => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $data['is_active'] = $request->boolean('is_active', true);

        $old = $category->toArray();
        $category->update($data);
        ActivityLog::record('Updated category: ' . $category->name, $category, $old, $category->fresh()->toArray());

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->image) Storage::disk('public')->delete($category->image);
        ActivityLog::record('Deleted category: ' . $category->name, $category);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }

    public function toggle(Category $category)
    {
        $category->update(['is_active' => ! $category->is_active]);
        return back()->with('success', 'Status updated.');
    }
}
