<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Tag, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('products')->paginate(30);
        return view('admin.tags.index', compact('tags'));
    }

    public function create() { return view('admin.tags.create'); }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:tags|max:80']);
        $tag = Tag::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return redirect()->route('admin.tags.index')->with('success', 'Tag created.');
    }

    public function edit(Tag $tag) { return view('admin.tags.edit', compact('tag')); }

    public function update(Request $request, Tag $tag)
    {
        $request->validate(['name' => "required|string|unique:tags,name,{$tag->id}|max:80"]);
        $tag->update(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return redirect()->route('admin.tags.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag deleted.');
    }
}
