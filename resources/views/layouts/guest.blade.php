<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0047bb">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
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
        input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            border: 1px solid #c4c8d1;
            accent-color: #0047bb;
        }
    </style>
</head>
<body class="font-sans text-[#16181d] antialiased">
    <div class="min-h-screen grid lg:grid-cols-2">
        <div class="hidden lg:flex flex-col justify-between bg-primary text-white p-12">
            <div>
                <span class="inline-flex rounded-lg bg-white px-4 py-3">
                    <img src="{{ asset('logo.png') }}" alt="PAK Precision Tools" class="h-12 w-auto">
                </span>
                <p class="mt-10 text-xs font-semibold tracking-[0.2em] uppercase text-white/70">Staff inbox</p>
                <h1 class="mt-3 text-4xl font-semibold leading-tight">Review and update incoming enquiries.</h1>
                <p class="mt-4 text-white/80 max-w-md">Messages, sourcing requests, and quote requests from every storefront are collected here.</p>
            </div>
            <p class="text-sm text-white/70">PAK Precision Tools</p>
        </div>

        <div class="flex flex-col justify-center px-6 py-12 bg-[#f1f3f7]">
            <div class="w-full max-w-md mx-auto">
                <div class="bg-white shadow-sm border border-[#d8dbe2] rounded-2xl p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
