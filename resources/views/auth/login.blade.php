<x-auth-split-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-xs uppercase tracking-widest text-indigo-600 font-semibold">Zaka Management System</h2>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">Bombambili Parish</h1>
        <p class="text-sm text-gray-600 mt-2">Sign in to continue. Accounts are created by the admin.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email or Phone -->
        <div>
            <x-input-label for="login" :value="__('Email au Namba ya Simu')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="email@example.com au 0712345678" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 space-y-4">
            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
            <x-primary-button class="w-full justify-center py-3 text-sm shadow-md">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-auth-split-layout>
