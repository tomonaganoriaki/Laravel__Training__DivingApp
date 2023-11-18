<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ '商品編集' }}
        </h2>
    </x-slot>

    <div class="py-12 font-bold">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST"  action="{{ route('admin.product.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 pb-0">
                        <x-input-label for="name" value="新商品名" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{$product->name}}" required autofocus autocomplete="name" />
                        <x-input-label for="description" value="商品説明" style="margin-top: 20px" />
                        <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" value="{{$product->description}}" required autofocus autocomplete="description" />
                        <x-input-label for="price" value="価格（円は不要）" style="margin-top: 20px" />
                        <x-text-input id="price" class="block mt-1 w-full" type="text" name="price" value="{{$product->price}}" required autofocus autocomplete="price" />
                        <x-input-label for="stock" value="初期在庫数" style="margin-top: 20px" />
                        <x-text-input id="stock" class="block mt-1 w-full" type="text" name="stock" value="{{$product->stock}}" required autofocus autocomplete="stock" />
                        <x-input-label for="category" value="カテゴリー名" style="margin-top: 20px" />
                        <select class="block mt-1 w-full" name="category" style="border: 1px solid rgb(210 213 219); border-radius:8px;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if($category->id == $product->category_id) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-label for="tag" value="タグ" style="margin-top: 20px"/>
                        <div class="flex" style="align-items: center;">
                            @foreach($tags as $tag)
                                <input type="checkbox" name="tag[]" value="{{$tag->id}}"
                                    @if($product->tags->contains($tag->id)) checked @endif
                                    style="margin-inline:16px 5px">{{$tag->name}}
                            @endforeach
                        </div>
                        <x-input-label for="image" value="画像（変更時のみ選択）" style="margin-top: 20px"/>
                        <div class="flex" style="align-items: center;">
                            <input type="file" name="updateImage" style="margin-inline:16px 5px">
                            @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}" style="width: 180px; height: 100px; margin-left:20px;">
                        @endforeach
                    </div>
                    <div class="p-6 text-gray-900">
                        <input class="button" type="button" onclick="location.href='{{ route('admin.product.index') }}'" value="戻る" style="margin-right: 6px">
                        <input class="button" type="submit" value="更新">
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>