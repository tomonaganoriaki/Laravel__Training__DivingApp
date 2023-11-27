<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['categories', 'tags', 'images']);
        $keyword = $request->keyword;
        $upper = $request->upper;
        $lower = $request->lower; 
        $selectCategory = $request->selectCategory;

        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        if(!empty($upper)) {
            $query->where('price', '<=', $upper);
        }
        if(!empty($lower)) {
            $query->where('price', '>=', $lower);
        }
        if(!empty($selectCategory)){
            $query->whereHas('categories', function ($query) use ($selectCategory) {
                $query->where('category_id', $selectCategory);
            });
        }

        $products = $query->get();
        $categories = Category::all();
        return view('admin.product.index', compact('products', 'categories', 'keyword', 'upper', 'lower', 'selectCategory'));
    }


    public function create(): View
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.product.create', compact('tags', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'integer'],
            'stock' => ['required', 'integer'],
            'category' => ['required'],
            'tag' => ['required'],
        ]); 

        DB::beginTransaction();
        try{
            $savedProduct = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);
            $savedProduct->categories()->sync($request->category);
            $savedProduct->tags()->sync($request->tag);

            $img = $request->file('img_path');
            $imgPath = $img->store('img','public');
            $imgTableData = new Image;
            $imgTableData->path = $imgPath;
            $imgTableData->product_id = $savedProduct->id;
            $imgTableData->save();

            DB::commit();

            session()->flash('flash_message', '商品「' . $savedProduct->name . '」を作成しました。');
            return redirect()->route('admin.product.index');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            session()->flash('flash_message', '商品の作成に失敗しました。');
            return redirect()->route('admin.product.index');
        }
    }

    public function edit(int $id): View
    {
        $product = Product::findOrFail($id);
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'tags', 'categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'integer'],
            'stock' => ['required', 'integer'],
            'category' => ['required'],
            'tag' => ['required'],
        ]); 

        DB::beginTransaction();
        try{
            $product = Product::findOrFail($id);

            $updateImg = $request->file('updateImage');
            if ($updateImg) {
                $productImg = Image::where('product_id', $id)->pluck('path')->first();
                Storage::disk('public')->delete($productImg);
                $product->images()->delete(); 
                $imgPath = $updateImg->store('img','public');
                $imgTableData = new Image;
                $imgTableData->path = $imgPath;
                $imgTableData->product_id = $product->id;
                $imgTableData->save();
            }
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ]);
            $product->categories()->sync($request->category);
            $product->tags()->sync($request->tag);

            DB::commit();

            session()->flash('flash_message', '商品を更新しました。');
            return redirect()->route('admin.product.index');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            session()->flash('flash_message', '商品の更新に失敗しました。');
            return redirect()->route('admin.product.index');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        DB::beginTransaction();
        try{
            $product = Product::findOrFail($id);
            $productImgPath = Image::where('product_id', $id)->pluck('path')->first();
            Storage::disk('public')->delete($productImgPath);

            $product->images()->delete(); 
            $product->delete();
            $product->categories()->detach();
            $product->tags()->detach();

            DB::commit();

            session()->flash('flash_message', '商品を削除しました。');
            return redirect()->route('admin.product.index');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            session()->flash('flash_message', '商品の削除に失敗しました。');
            return redirect()->route('admin.product.index');
        }
    }

    public function exportCsv()
    {
        $products = Product::with('categories', 'tags' , 'images')
            ->select('id', 'name', 'description', 'price', 'stock')
            ->get();

        $csvHeader = ['id', '商品名', '商品説明', '価格', '在庫数', 'カテゴリー', 'タグ', '画像パス（変更厳禁）'];
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
                $product->images->pluck('path')->implode(', '),
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

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $handle = fopen($path, 'r');
            $header = fgetcsv($handle); 
            while ($row = fgetcsv($handle)) {
                $data = array_combine($header, $row);
                $validator = Validator::make($data, [
                    '商品名' => 'required|string|max:20',
                    '商品説明' => 'required|string|max:1000',
                    '価格' => 'required|integer',
                    '在庫数' => 'required|integer',
                    'カテゴリー' => 'required|string',
                    'タグ' => 'required|string',
                ]);
                
                $product = Product::find($data['id']) ?? new Product;
                $product->name = $data['商品名'];
                $product->description = $data['商品説明'];
                $product->price = $data['価格'];
                $product->stock = $data['在庫数'];
                $product->save();
                $categories = explode(', ', $data['カテゴリー']);
                $categoryIds = [];
                foreach ($categories as $categoryName) {
                    $category = Category::firstOrCreate(['name' => $categoryName]);
                    $categoryIds[] = $category->id;
                }
                $product->categories()->sync($categoryIds);
                $tags = explode(', ', $data['タグ']);
                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
                $product->tags()->sync($tagIds);
            }
            fclose($handle);

            DB::commit();
            
            session()->flash('flash_message', 'CSVデータのインポートが完了しました。');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            session()->flash('flash_message', 'CSVデータのインポートに失敗しました。');
            return redirect()->back();
        }
    }
}