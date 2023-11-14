<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::all();
        return view('admin.category.index')->with('categories', $categories);
    }

    public function create(): View
    {
        return view('admin.category.create');
    }

    public function store(Request $request): RedirectResponse
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

    public function edit($id): View
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit')->with('category', $category);
    }

    public function update(Request $request, $id): RedirectResponse
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

    public function destroy($id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
        session()->flash('flash_message', 'カテゴリーを削除しました。');
        return redirect()->route('admin.category.index');
    }
}
