<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ '商品一覧' }}
        </h2>
    </x-slot>

    <div class="py-12 font-bold">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('flash_message'))
                <div class="flash_message">
                    <p class="text-green-500">{{ session('flash_message') }}</p>
                </div>
            @endif
            <div class="flex" style="margin-bottom:20px; align-items:center; justify-content:end">
                <form action="{{ route('admin.product.csvImport') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label><input type="file" name="file" style="width:350px"></label>
                    <button class="button">CSVインポート</button>
                </form>
                <button class="button" type="button" onclick="location.href='{{ route('admin.product.csvExport') }}'" style="margin-inline:7px 5px">CSVエクスポート</button>
                <button class="button" type="button" onclick="location.href='{{ route('admin.product.create') }}'">新規作成</button>
            </div>
            <div class="flex items-center justify-end" style="margin-block: 30px">
                <form action="{{ route('admin.product.index') }}" method="GET">
                    @csrf
                    <input placeholder="KW部分一致検索" type="text" name="keyword" value="{{$keyword}}">
                    <input placeholder="料金上限を入力" type="text" name="upper" value="{{$upper}}">
                    <input placeholder="料金下限を入力" type="text" name="lower" value="{{$lower}}">
                    <select name="category">
                        <option value="">全カテゴリー</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if($category->id == $selectCategory) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input class="button mx-1.5" type="submit" value="検索">
                </form>
                <form action="{{ route('admin.product.index') }}" method="GET">
                    @csrf
                    <input class="button" type="submit" value="全商品表示">
                </form>
            </div>
            @foreach($products as $product)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex items-center">
                    <div class="p-6 text-gray-900">
                        <p>商品名　 ： {{ $product->name }}</p>
                        <p>商品説明 ： {{ $product->description }}</p>
                        <p>価格　　 ： {{ $product->price }}円</p>
                        <p>在庫数　 ： {{ $product->stock }}個</p>
                        @foreach($product->categories as $category)
                            <p>カテゴリ ： {{ $category->name }}</p>
                        @endforeach
                        <div class="flex mb-2">
                            <p>タグ　　 ： </p>
                            @foreach($product->tags as $tag)
                                <p style="padding-right: 10px">{{ $tag->name }}</p>
                            @endforeach
                        </div>
                        <p>{{ $product->tag }}</p>
                        <button class="button" type="button" onclick="location.href='{{ route('admin.product.edit', $product->id) }}'" style="margin-right: 6px">編集</button>
                        <form method="POST" action="{{ route('admin.product.destroy', $product->id) }}" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <input class="button" type="submit" value="削除" onClick="delete_alert(event);return false;">
                        </form>
                        <script>
                            function delete_alert(e){
                                if(!window.confirm('本当に削除しますか？')){
                                    window.alert('キャンセルされました');
                                    return false;
                                }
                                document.deleteform.submit();
                            }
                        </script>
                    </div>
                    <div>
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}" style="width: 300px; height: 165px; margin-left:20px;">
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>