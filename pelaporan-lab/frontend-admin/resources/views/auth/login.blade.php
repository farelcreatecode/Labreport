<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-900">Login Administrator</h2>
        <p class="text-xs text-gray-500 mt-1">Masuk untuk mengelola laporan dan penugasan teknisi</p>
    </div>

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-xs rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Administrator')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', 'admin@labreport.com')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-2.5 bg-indigo-600 hover:bg-indigo-700">
                {{ __('Masuk ke Dashboard') }}
            </x-primary-button>
        </div>

        <div class="pt-2 text-center border-t border-gray-100">
            <a href="{{ env('PELAPOR_PORTAL_URL', 'http://127.0.0.1:8000') }}" class="text-xs text-indigo-600 hover:underline">
                &larr; Buka Portal Pelaporan Publik (Port 8000)
            </a>
        </div>
    </form>
</x-guest-layout>
