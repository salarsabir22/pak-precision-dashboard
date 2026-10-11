@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm text-[#16181d] shadow-none placeholder:text-slate-400 focus:border-[#0047bb] focus:outline-none focus:ring-2 focus:ring-[#0047bb]/20 disabled:bg-slate-50 disabled:text-slate-500']) !!}>
