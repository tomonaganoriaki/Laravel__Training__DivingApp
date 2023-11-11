<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ '管理者アカウント編集' }}
        </h2>
    </x-slot>

    <div class="py-12 font-bold">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST"  action="{{ route('admin.account.update', $admin->id) }}">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>氏名　：<input type="text" name="name" value="{{ $admin->name }}"></p>
                        <p>メール：<input type="text" name="email" value="{{ $admin->email }}"></p>
                        <p>パスワード：<input type="password" name="password" value=""></p>
                        <p>作成日：{{ $admin->created_at }}</p>
                    </div>
                    <div class="p-6 text-gray-900">
                        <input type="button" onclick="location.href='{{ route('admin.account.index') }}'" value="戻る">
                        <input type="submit" value="更新">
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>