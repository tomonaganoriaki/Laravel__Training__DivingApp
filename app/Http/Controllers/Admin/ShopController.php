<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::all();
        return view('admin.shop.index')->with('shops', $shops);
    }

    public function create(): View
    {
        return view('admin.shop.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
        ]);
        $shop = Shop::create([
            'name' => $request->name,
        ]);
        session()->flash('flash_message', 'タグを作成しました。');
        return redirect()->route('admin.shop.index');
    }

    public function edit($id): View
    {
        $shop = Shop::findOrFail($id);
        return view('admin.shop.edit')->with('shop', $shop);
    }

    public function update(Request $request, $id): View
    {
        $shop = Shop::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
        ]);
        $shop->update([
            'name' => $request->name,
        ]);
        session()->flash('flash_message', 'タグを更新しました。');
        return redirect()->route('admin.shop.index');
    }

    public function destroy($id): RedirectResponse
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();
        session()->flash('flash_message', 'タグを削除しました。');
        return redirect()->route('admin.shop.index');
    }
}
