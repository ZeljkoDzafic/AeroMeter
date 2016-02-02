<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index()
    {
        $tags = \App\Tag::get();
        return view('backend.tags.index', compact('tags'));
    }

    public function show(Request $request, $id) {
        $tag = \App\Tag::findOrFail($id);
        return view('backend.tags.show', compact('tag'));
    }

    public function create(Request $request) {
        return view('backend.tags.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:tags,name'
        ]);

        $tag = new \App\Tag();
        $tag->name = strtolower($request->input('name'));
        $tag->save();
        return redirect('backend/tags' . $tag->id);
    }

    public function edit(Request $request, $id) {
        $tag = \App\Tag::findOrFail($id);
        return view('backend.tags.edit', compact('tag'));
    }

    public function update(Request $request, $id) {
        $tag = \App\Tag::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|unique:tags,name,' . $tag->id
        ]);
        $tag->name = strtolower($request->input('name'));
        $tag->save();
        return redirect('backend/tags/' . $tag->id);
    }

    public function destroy(Request $request, $id)
    {
        $tag = \App\Tag::findOrFail($id);
        $tag->delete();

        return redirect()->to('backend/tags');
    }
}
