<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of tags
     */
    public function index()
    {
        $tags = Tag::withCount(['destinations', 'hotels', 'restaurants'])
            ->latest()
            ->paginate(15);

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        if (!isset($validated['color'])) {
            $validated['color'] = '#3B82F6'; // Default blue
        }

        Tag::create($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil ditambahkan!');
    }

    /**
     * Display the specified tag
     */
    public function show(Tag $tag)
    {
        $tag->loadCount(['destinations', 'hotels', 'restaurants']);
        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified tag
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tag->update($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil diperbarui!');
    }

    /**
     * Remove the specified tag
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil dihapus!');
    }
}


