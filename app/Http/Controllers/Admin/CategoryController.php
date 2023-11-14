<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index')->with('categories', $categories);
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $category = Category::create([
            'name' => $request->name,
        ]);
        session()->flash('flash_message', 'カテゴリーを作成しました。');
        return redirect()->route('admin.category.index');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit')->with('category', $category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $category->update([
            'name' => $request->name,
        ]);
        session()->flash('flash_message', 'カテゴリーを更新しました。');
        return redirect()->route('admin.category.index');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        session()->flash('flash_message', 'カテゴリーを削除しました。');
        return redirect()->route('admin.category.index');
    }
}
