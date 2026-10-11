<x-app-layout>
    <x-slot name="header">Submissions</x-slot>
    <x-slot name="title">Submissions</x-slot>

    @if (auth()->user()->isSuperAdmin())
        <div class="mb-4 flex justify-end">
            <a href="{{ route('submissions.create') }}" class="rounded-lg bg-primary text-white px-4 py-2 text-sm">Add submission</a>
        </div>
    @endif

    <form method="GET" class="rounded-2xl bg-white border border-slate-200 p-4 mb-5 grid gap-3 md:grid-cols-4">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search name, email, reference" class="rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm md:col-span-2 focus:border-[#0047bb] focus:outline-none focus:ring-2 focus:ring-[#0047bb]/20">
        <select name="site" class="rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm focus:border-[#0047bb] focus:outline-none focus:ring-2 focus:ring-[#0047bb]/20">
            <option value="">All sites</option>
            @foreach ($sites as $site)
                <option value="{{ $site->id }}" @selected(($filters['site'] ?? '') == $site->id)>{{ $site->name }}</option>
            @endforeach
        </select>
        <select name="type" class="rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm focus:border-[#0047bb] focus:outline-none focus:ring-2 focus:ring-[#0047bb]/20">
            <option value="">All types</option>
            <option value="contact" @selected(($filters['type'] ?? '') === 'contact')>Message</option>
            <option value="sourcing" @selected(($filters['type'] ?? '') === 'sourcing')>Sourcing</option>
            <option value="inquiry" @selected(($filters['type'] ?? '') === 'inquiry')>Quote</option>
        </select>
        <select name="status" class="rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm focus:border-[#0047bb] focus:outline-none focus:ring-2 focus:ring-[#0047bb]/20">
            <option value="">All statuses</option>
            <option value="new" @selected(($filters['status'] ?? '') === 'new')>New</option>
            <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>In progress</option>
            <option value="done" @selected(($filters['status'] ?? '') === 'done')>Done</option>
            <option value="spam" @selected(($filters['status'] ?? '') === 'spam')>Spam</option>
        </select>
        <div class="md:col-span-4 flex gap-2">
            <button class="rounded-lg bg-primary text-white px-4 py-2 text-sm">Filter</button>
            <a href="{{ route('submissions.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">Reset</a>
        </div>
    </form>

    <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Reference</th>
                    <th class="px-4 py-3 font-medium">From</th>
                    <th class="px-4 py-3 font-medium">Site</th>
                    <th class="px-4 py-3 font-medium">Type</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">When</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($submissions as $submission)
                    <tr
                        class="cursor-pointer hover:bg-slate-50 {{ $submission->status === 'new' ? 'font-medium' : '' }}"
                        onclick="window.location='{{ route('submissions.show', $submission) }}'"
                    >
                        <td class="px-4 py-3">
                            <span class="text-primary">{{ $submission->reference }}</span>
                            @if ($submission->attachments_count)
                                <span class="ml-1 text-xs text-slate-400">{{ $submission->attachments_count }} file{{ $submission->attachments_count > 1 ? 's' : '' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div>{{ $submission->name }}</div>
                            <div class="text-slate-500 font-normal">{{ $submission->email }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $submission->site->name }}</td>
                        <td class="px-4 py-3">{{ $submission->typeLabel() }}</td>
                        <td class="px-4 py-3">{{ $submission->statusLabel() }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $submission->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-500">No submissions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($submissions->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $submissions->links() }}</div>
        @endif
    </div>
</x-app-layout>
