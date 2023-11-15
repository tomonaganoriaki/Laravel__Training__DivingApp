<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['categories', 'tags'])->get();
        return view('admin.product.index')->with('products', $products);
    }

    public function create(): View
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.product.create')->with('tags', $tags)->with('categories', $categories);
    }

    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:20'],
                'description' => ['required', 'string', 'max:1000'],
                'price' => ['required', 'integer'],
                'stock' => ['required', 'integer'],
                'category' => ['required'],
                'tag' => ['required'],
            ]); 
            $isSaveProduct = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);
            $isSaveProductCategory = $isSaveProduct->categories()->sync($request->category);
            $isSaveProductTag = $isSaveProduct->tags()->sync($request->tag);
            if (!$isSaveProduct || !$isSaveProductCategory || !$isSaveProductTag) {
                DB::rollBack();
                session()->flash('flash_message', '商品の作成に失敗しました。');
                return redirect()->route('admin.product.index');
            }
            DB::commit();
            session()->flash('flash_message', '商品「' . $isSaveProduct->name . '」を作成しました。');
            return redirect()->route('admin.product.index');
            } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('flash_message', '商品の作成に失敗しました。');
            return redirect()->route('admin.product.index');
        }
    }

    public function edit($id): View
    {
        $product = Product::findOrFail($id);
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.product.edit')->with('product', $product)->with('tags', $tags)->with('categories', $categories);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        DB::beginTransaction();
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:20'],
                'description' => ['required', 'string', 'max:1000'],
                'price' => ['required', 'integer'],
                'stock' => ['required', 'integer'],
                'category' => ['required'],
                'tag' => ['required'],
            ]); 
        $product = Product::findOrFail($id);
        $isUpdateProduct = $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock
        ]);
        $isUpdateProductCategory = $product->categories()->sync($request->category);
        $isUpdateProductTag = $product->tags()->sync($request->tag);
        if (!$isUpdateProduct || !$isUpdateProductCategory || !$isUpdateProductTag) {
            DB::rollBack();
            session()->flash('flash_message', '商品の更新に失敗しました。');
            return redirect()->route('admin.product.index');
        }
        DB::commit();
        session()->flash('flash_message', '商品を更新しました。');
        return redirect()->route('admin.product.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('flash_message', '商品の更新に失敗しました。');
            return redirect()->route('admin.product.index');
        }
    }

    public function destroy($id): RedirectResponse
    {
        DB::beginTransaction();
        try{
            $product = Product::findOrFail($id);
            $isDeleteProduct = $product->delete();
            $isDeleteProductCategory = $product->categories()->detach();
            $isDeleteProductTag = $product->tags()->detach();
            if (!$isDeleteProduct || !$isDeleteProductCategory || !$isDeleteProductTag) {
                DB::rollBack();
                session()->flash('flash_message', '商品の削除に失敗しました。');
                return redirect()->route('admin.product.index');
            }
            DB::commit();
            session()->flash('flash_message', '商品を削除しました。');
            return redirect()->route('admin.product.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('flash_message', '商品の削除に失敗しました。');
            return redirect()->route('admin.product.index');
        }
    }
}