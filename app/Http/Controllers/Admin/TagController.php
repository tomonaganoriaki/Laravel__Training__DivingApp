<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tag.index')->with('tags', $tags);
    }

    public function create()
    {
        return view('admin.tag.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
        ]);
        $tag = Tag::create([
            'name' => $request->name,
        ]);
        session()->flash('flash_message', 'タグを作成しました。');
        return redirect()->route('admin.tag.index');
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tag.edit')->with('tag', $tag);
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
        ]);
        $tag->update([
            'name' => $request->name,
        ]);
        session()->flash('flash_message', 'タグを更新しました。');
        return redirect()->route('admin.tag.index');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        session()->flash('flash_message', 'タグを削除しました。');
        return redirect()->route('admin.tag.index');
    }
}
