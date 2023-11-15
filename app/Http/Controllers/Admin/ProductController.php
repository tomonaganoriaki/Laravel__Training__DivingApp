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
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function exportCsv()
    {
        $products = Product::with('categories', 'tags')
            ->select('id', 'name', 'description', 'price', 'stock')
            ->get();

        $csvHeader = ['id', '商品名', '商品説明', '価格', '在庫数', 'カテゴリー', 'タグ'];
        $csvData = [];

        foreach ($products as $product) {
            $categories = $product->categories->pluck('name')->implode(', ');
            $tags = $product->tags->pluck('name')->implode(', ');

            $csvData[] = [
                $product->id,
                $product->name,
                $product->description,
                $product->price,
                $product->stock,
                $categories,
                $tags,
            ];
        }

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ]);

        return $response;
    }
}