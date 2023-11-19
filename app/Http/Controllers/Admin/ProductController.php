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

class ProductController extends Controller
{
    public function index(): View
    {
    $query = Product::with(['categories', 'tags', 'images']);

    $keyword = request()->input('keyword');
    $upper = request()->input('upper');
    $lower = request()->input('lower'); 
    $selectCategory = request()->input('category');

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
            $img = $request->file('img_path');
            $imgPath = $img->store('img','public');
            $imgTableData = new Image;
            $imgTableData->path = $imgPath;
            $imgTableData->product_id = $isSaveProduct->id;
            $isSaveImg = $imgTableData->save();
            if (!$isSaveProduct || !$isSaveProductCategory || !$isSaveProductTag || !$isSaveImg) {
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
            $updateImg = $request->file('updateImage');
            if ($updateImg) {
                $productImg = Image::where('product_id', $id)->pluck('path')->first();
                $isDeleteStorageImg =  Storage::disk('public')->delete($productImg);
                $isDeleteRecordImg = $product->images()->delete(); 
                $imgPath = $updateImg->store('img','public');
                $imgTableData = new Image;
                $imgTableData->path = $imgPath;
                $imgTableData->product_id = $product->id;
                $isSaveImg = $imgTableData->save();
                if (!$isDeleteStorageImg || !$isDeleteRecordImg || !$isSaveImg) {
                    DB::rollBack();
                    session()->flash('flash_message', '画像更新が失敗したため商品の更新に失敗しました。');
                    return redirect()->route('admin.product.index');
                }
            }
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
            $productImgPath = Image::where('product_id', $id)->pluck('path')->first();
            $isDeleteStorageImg =  Storage::disk('public')->delete($productImgPath);
            $isDeleteProductImgPath = $product->images()->delete(); 
            $isDeleteProduct = $product->delete();
            $isDeleteProductCategory = $product->categories()->detach();
            $isDeleteProductTag = $product->tags()->detach();
            if (!$isDeleteProduct || !$isDeleteProductCategory || !$isDeleteProductTag || !$isDeleteProductImgPath || !$isDeleteStorageImg) {
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
        $file = $request->file('file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle); 
        while ($row = fgetcsv($handle)) {
            $data = array_combine($header, $row);
            $product = Product::find($data['id']) ?? new Product;
            $product->name = $data['商品名'];
            $product->description = $data['商品説明'];
            $product->price = $data['価格'];
            $product->stock = $data['在庫数'];
            $product->save();
            if (!empty($data['カテゴリー'])) {
                $categories = explode(', ', $data['カテゴリー']);
                $categoryIds = [];
                foreach ($categories as $categoryName) {
                    $category = Category::firstOrCreate(['name' => $categoryName]);
                    $categoryIds[] = $category->id;
                }
                $product->categories()->sync($categoryIds);
            }
            if (!empty($data['タグ'])) {
                $tags = explode(', ', $data['タグ']);
                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
                $product->tags()->sync($tagIds);
            }
        }
        fclose($handle);
        session()->flash('flash_message', 'CSVデータのインポートが完了しました。');
        return redirect()->back();
    }


}