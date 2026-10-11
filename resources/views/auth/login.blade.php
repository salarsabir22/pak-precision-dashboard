<x-guest-layout>
    <h1 class="text-xl font-semibold text-ink">Sign in</h1>
    <p class="mt-1 mb-6 text-sm text-slate-500">Use your staff account to open the inbox.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border border-[#c4c8d1] text-primary focus:ring-primary" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (\App\Models\User::query()->doesntExist())
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                    Create an account
                </a>
            @else
                <span></span>
            @endif
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        @if (Route::has('password.request'))
            <div class="mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    Forgot password
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
