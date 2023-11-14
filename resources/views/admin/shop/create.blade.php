<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ '店舗新規作成' }}
        </h2>
    </x-slot>
    <div class="py-12 font-bold">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.shop.store') }}">
                @csrf
                <div>
                    <x-input-label for="name" value="新店舗名" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button onclick="location.href='{{ route('admin.shop.index') }}'">
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