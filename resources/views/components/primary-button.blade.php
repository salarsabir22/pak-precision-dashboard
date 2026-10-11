<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#0047bb] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#003a99] focus:bg-[#003a99] active:bg-[#003088] focus:outline-none focus:ring-2 focus:ring-[#0047bb] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
