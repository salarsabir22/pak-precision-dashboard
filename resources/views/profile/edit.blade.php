<x-app-layout>
    <x-slot name="header">Profile</x-slot>
    <x-slot name="title">Profile</x-slot>

    <div class="max-w-xl space-y-6">
        <div class="rounded-2xl bg-white border border-slate-200 p-6">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-6">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</x-app-layout>
