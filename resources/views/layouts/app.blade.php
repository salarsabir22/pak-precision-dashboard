<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0047bb">
    <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Figtree', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        primary: '#0047bb',
                        charcoal: '#47484b',
                        ink: '#1f2023',
                    }
                }
            }
        }
    </script>
    <style>
        input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="submit"]):not([type="button"]),
        select,
        textarea {
            border: 1px solid #c4c8d1;
            border-radius: 0.5rem;
            background: #fff;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #0047bb;
            box-shadow: 0 0 0 3px rgba(0, 71, 187, 0.18);
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#f1f3f7] text-[#16181d]">
    @php
        $unread = \App\Models\Submission::query()->where('status', 'new')->count();
    @endphp
    <div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]" x-data="{ open: false }">
        <aside class="bg-ink text-white lg:min-h-screen lg:sticky lg:top-0">
            <div class="flex items-center justify-between px-4 h-16 border-b border-white/10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
                    <span class="rounded-md bg-white px-2 py-1 shrink-0">
                        <img src="{{ asset('logo.png') }}" alt="PAK Precision Tools" class="h-8 w-auto">
                    </span>
                    <span class="text-sm font-semibold leading-tight">Inbox</span>
                </a>
                <button class="lg:hidden text-slate-300" @click="open = !open" type="button">Menu</button>
            </div>
            <nav class="px-3 py-4 space-y-1" :class="open ? 'block' : 'hidden lg:block'">
                <a href="{{ route('dashboard') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-white/5' }}">Overview</a>
                <a href="{{ route('submissions.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('submissions.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <span>Submissions</span>
                    @if ($unread)
                        <span class="rounded-full bg-white text-primary text-[11px] font-semibold px-2 py-0.5">{{ $unread }}</span>
                    @endif
                </a>
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('users.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('users.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-white/5' }}">Accounts</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('profile.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-white/5' }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button class="w-full text-left rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5">Log out</button>
                </form>
            </nav>
        </aside>

        <div>
            <header class="bg-white border-b border-[#d8dbe2] h-16 flex items-center justify-between px-6">
                <div>
                    @isset($header)
                        <div class="text-lg font-semibold text-ink">{{ $header }}</div>
                    @endisset
                </div>
                <div class="text-sm text-charcoal">{{ Auth::user()->name }} <span class="text-slate-400">· {{ Auth::user()->isSuperAdmin() ? 'Superadmin' : 'User' }}</span></div>
            </header>
            <main class="p-6">
                @if (session('status'))
                    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
