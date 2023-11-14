<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'ニュース新規作成' }}
        </h2>
    </x-slot>
    <div class="py-12 font-bold">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.news.store') }}">
                @csrf
                <div>
                    <x-input-label for="title" value="タイトル" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus autocomplete="title" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="content" value="本文" />
                    <x-text-input id="content" class="block mt-1 w-full" type="content" name="content" :value="old('content')" required autocomplete="content" />
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button onclick="location.href='{{ route('admin.news.index') }}'">
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