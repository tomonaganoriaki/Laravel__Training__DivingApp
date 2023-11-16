<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ '商品新規作成' }}
        </h2>
    </x-slot>
    <div class="py-12 font-bold">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <x-input-label for="name" value="新商品名" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="" required autofocus autocomplete="name" />
                    <x-input-label for="description" value="商品説明" style="margin-top: 20px" />
                    <textarea id="description" class="block mt-1 w-full" type="text" name="description" value="" required autofocus autocomplete="description" style="border: 1px solid rgb(210 213 219); border-radius:8px; height: 100px;"></textarea>
                    <x-input-label for="price" value="価格（円は不要）" style="margin-top: 20px" />
                    <x-text-input id="price" class="block mt-1 w-full" type="text" name="price" value="" required autofocus autocomplete="price" />
                    <x-input-label for="stock" value="初期在庫数" style="margin-top: 20px" />
                    <x-text-input id="stock" class="block mt-1 w-full" type="text" name="stock" value="100" required autofocus autocomplete="stock" />
                    <x-input-label for="category" value="カテゴリー名" style="margin-top: 20px" />
                        <select class="block mt-1 w-full" name="category" style="border: 1px solid rgb(210 213 219); border-radius:8px;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    <x-input-label for="tag" value="タグ" style="margin-top: 20px"/>
                    <div class="flex" style="align-items: center;">
                        @foreach($tags as $tag)
                            <input type="checkbox" name="tag[]" value="{{$tag->id}}" style="margin-inline:16px 5px">{{$tag->name}}
                        @endforeach
                    </div>
                    <x-input-label for="image" value="商品画像" style="margin-block: 20px 6px;" />
                    <input type="file" name="img_path">
                </div>
                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button onclick="location.href='{{ route('admin.product.index') }}'">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-primary-button class="ml-4">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>