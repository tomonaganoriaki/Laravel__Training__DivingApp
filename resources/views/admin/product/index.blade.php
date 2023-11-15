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
            <div style="text-align: right; margin-bottom:20px">
                <button class="button" type="button" onclick="location.href='{{ route('admin.product.csvExport') }}'">CSVインポート</button>
                <button class="button" type="button" onclick="location.href='{{ route('admin.product.csvExport') }}'" style="margin-inline:7px 5px">CSVエクスポート</button>
                <button class="button" type="button" onclick="location.href='{{ route('admin.product.create') }}'">新規作成</button>
            </div>
            @foreach($products as $product)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>