<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'タグ一覧' }}
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
                <button class="button" type="button" onclick="location.href='{{ route('admin.tag.create') }}'">新規作成</button>
            </div>
            @foreach($tags as $tag)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>{{ $tag->name }}</p>
                        <button class="button" type="button" onclick="location.href='{{ route('admin.tag.edit', $tag->id) }}'" style="margin-right: 6px">編集</button>
                        <form method="POST" action="{{ route('admin.tag.destroy', $tag->id) }}" style="display: inline">
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