<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::all();
        return view('admin.brand.index')->with('brands', $brands);
    }

    public function create(): View
    {
        return view('admin.brand.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
        ]);
        $brand = Brand::create([
            'name' => $request->name,
        ]);
        session()->flash('flash_message', 'タグを作成しました。');
        return redirect()->route('admin.brand.index');
    }

    public function edit($id): View
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit')->with('brand', $brand);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $brand = Brand::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
        ]);
        $brand->update([
            'name' => $request->name,
        ]);
        session()->flash('flash_message', 'タグを更新しました。');
        return redirect()->route('admin.brand.index');
    }

    public function destroy($id): RedirectResponse
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        session()->flash('flash_message', 'タグを削除しました。');
        return redirect()->route('admin.brand.index');
    }
}
