<x-guest-layout>
    <h1 class="text-xl font-semibold text-ink">Reset password</h1>
    <p class="mt-1 mb-6 text-sm text-slate-500">Enter your email address and we will send a reset link.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 mr-4" href="{{ route('login') }}">
                Sign in
            </a>
            <x-primary-button>
                Send reset link
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
