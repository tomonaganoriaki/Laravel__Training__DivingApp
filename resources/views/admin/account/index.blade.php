<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ '管理者アカウント一覧' }}
        </h2>
    </x-slot>

    <div class="py-12 font-bold">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('flash_message'))
                <div class="flash_message">
                    <p class="text-green-500">{{ session('flash_message') }}</p>
                </div>
            @endif
            <button type="button" onclick="location.href='{{ route('admin.account.create') }}'">新規作成</button>
            @foreach($admins as $admin)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>氏名　：{{ $admin->name }}</p>
                        <p>メール：{{ $admin->email }}</p>
                        <p>作成日：{{ $admin->created_at }}</p>
                    </div>
                    <div class="p-6 text-gray-900">
                        {{-- <button type="button" onclick="location.href='{{ route('admin.account.edit', $admin->id) }}'">編集</button> --}}
                        <form method="POST" action="{{ route('admin.account.destroy', $admin->id) }}" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="削除" onClick="delete_alert(event);return false;">
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