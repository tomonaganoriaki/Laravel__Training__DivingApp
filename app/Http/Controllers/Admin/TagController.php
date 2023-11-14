<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::all();
        return view('admin.tag.index')->with('tags', $tags);
    }

    public function create(): View
    {
        return view('admin.tag.create');
    }

    public function store(Request $request): RedirectResponse
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

    public function edit($id): View
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tag.edit')->with('tag', $tag);
    }

    public function update(Request $request, $id): RedirectResponse
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

    public function destroy($id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        session()->flash('flash_message', 'タグを削除しました。');
        return redirect()->route('admin.tag.index');
    }
}
