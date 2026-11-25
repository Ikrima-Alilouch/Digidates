<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class AdminTagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name',
        ], [
            'name.unique' => 'Deze tag bestaat al.',
            'name.required' => 'Tag naam is verplicht.'
        ]);

        Tag::create(['name' => trim($validated['name'])]);

        return back()->with('success', 'Tag succesvol toegevoegd.');
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,' . $tag->id,
        ], [
            'name.unique' => 'Deze tag bestaat al.',
            'name.required' => 'Tag naam is verplicht.'
        ]);

        $tag->update(['name' => trim($validated['name'])]);

        return back()->with('success', 'Tag succesvol bewerkt.');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return back()->with('success', 'Tag succesvol verwijderd.');
    }
}
