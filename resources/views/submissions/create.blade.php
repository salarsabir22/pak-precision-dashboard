<x-app-layout>
    <x-slot name="header">Add submission</x-slot>
    <x-slot name="title">Add submission</x-slot>

    <form method="POST" action="{{ route('submissions.store') }}" class="max-w-2xl rounded-2xl bg-white border border-slate-200 p-6 space-y-4" x-data="{ type: '{{ old('type', 'contact') }}' }">
        @csrf
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Site</label>
                <select name="site_id" required class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}" @selected(old('site_id') == $site->id)>{{ $site->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Type</label>
                <select name="type" x-model="type" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
                    <option value="contact">Message</option>
                    <option value="sourcing">Sourcing</option>
                    <option value="inquiry">Quote</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Company</label>
                <input name="company" value="{{ old('company') }}" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Country</label>
                <input name="country" value="{{ old('country') }}" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Subject</label>
                <input name="subject" value="{{ old('subject') }}" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
        </div>
        <div x-show="type === 'sourcing'" class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Part name</label>
                <input name="part_name" value="{{ old('part_name') }}" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Brand</label>
                <input name="brand" value="{{ old('brand') }}" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Part number</label>
                <input name="part_number" value="{{ old('part_number') }}" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Quantity</label>
                <input name="quantity" type="number" min="1" value="{{ old('quantity') }}" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Message</label>
            <textarea name="message" rows="5" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm">{{ old('message') }}</textarea>
        </div>
        <button class="rounded-lg bg-primary text-white px-4 py-2 text-sm">Save submission</button>
    </form>
</x-app-layout>
