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
                    <div class="p-6 text-gray-900 pb-0">
                        <p style="margin-bottom: 15px">氏名　　　 ：　<input type="text" name="name" value="{{ $admin->name }}"></p>
                        <p style="margin-bottom: 15px">メール　　 ：　<input type="text" name="email" value="{{ $admin->email }}"></p>
                        <p style="margin-bottom: 15px">パスワード ：　<input type="password" name="password" value=""></p>
                    </div>
                    <div class="p-6 text-gray-900">
                        <input class="button" type="button" onclick="location.href='{{ route('admin.account.index') }}'" value="戻る" style="margin-right: 6px">
                        <input class="button" type="submit" value="更新">
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>