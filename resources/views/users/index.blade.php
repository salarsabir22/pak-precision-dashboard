<x-app-layout>
    <x-slot name="header">Accounts</x-slot>
    <x-slot name="title">Accounts</x-slot>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Role</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $account)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $account->name }}</td>
                            <td class="px-4 py-3">{{ $account->email }}</td>
                            <td class="px-4 py-3 capitalize">{{ $account->role }}</td>
                            <td class="px-4 py-3 text-right">
                                @if (! $account->is(auth()->user()))
                                    <form method="POST" action="{{ route('users.destroy', $account) }}" onsubmit="return confirm('Delete this account?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-700 text-sm underline">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="rounded-2xl bg-white border border-slate-200 p-6 space-y-4">
            @csrf
            <h2 class="font-semibold">Create account</h2>
            <div>
                <label class="block text-sm font-medium mb-1" for="name">Name</label>
                <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="role">Role</label>
                <select id="role" name="role" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
                    <option value="user" @selected(old('role', 'user') === 'user')>User — view only</option>
                    <option value="superadmin" @selected(old('role') === 'superadmin')>Superadmin — full access</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="password">Password</label>
                <input id="password" name="password" type="password" required class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
                @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <button class="rounded-lg bg-primary text-white px-4 py-2 text-sm">Create account</button>
        </form>
    </div>
</x-app-layout>
