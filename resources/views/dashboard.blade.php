<x-app-layout>
    <x-slot name="header">Overview</x-slot>
    <x-slot name="title">Overview</x-slot>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white border border-slate-200 p-5">
            <p class="text-sm text-slate-500">New</p>
            <p class="mt-2 text-3xl font-semibold text-ink">{{ $newCount }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5">
            <p class="text-sm text-slate-500">In progress</p>
            <p class="mt-2 text-3xl font-semibold text-ink">{{ $openCount }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Closed today</p>
            <p class="mt-2 text-3xl font-semibold text-ink">{{ $doneToday }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Active sites</p>
            <p class="mt-2 text-3xl font-semibold text-ink">{{ $siteCount }}</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section class="xl:col-span-2 rounded-2xl bg-white border border-slate-200">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold">Latest submissions</h2>
                <a href="{{ route('submissions.index') }}" class="text-sm text-primary underline">Open inbox</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recent as $submission)
                    <a href="{{ route('submissions.show', $submission) }}" class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50">
                        <div class="min-w-0">
                            <p class="font-medium truncate">{{ $submission->name }} <span class="text-slate-400 font-normal">· {{ $submission->site->name }}</span></p>
                            <p class="text-sm text-slate-500 truncate">{{ $submission->reference }} · {{ $submission->typeLabel() }} · {{ $submission->email }}</p>
                        </div>
                        <span class="shrink-0 text-xs rounded-full px-2.5 py-1 {{ $submission->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }}">{{ $submission->statusLabel() }}</span>
                    </a>
                @empty
                    <p class="px-5 py-10 text-sm text-slate-500">No submissions yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl bg-white border border-slate-200 p-5">
            <h2 class="font-semibold">By type</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><dt>Messages</dt><dd class="font-semibold">{{ $byType['contact'] }}</dd></div>
                <div class="flex justify-between"><dt>Sourcing</dt><dd class="font-semibold">{{ $byType['sourcing'] }}</dd></div>
                <div class="flex justify-between"><dt>Quotes</dt><dd class="font-semibold">{{ $byType['inquiry'] }}</dd></div>
            </dl>
            <h2 class="font-semibold mt-8">Sites</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @foreach ($bySite as $site)
                    <li class="flex justify-between gap-3">
                        <span class="truncate">{{ $site->name }}</span>
                        <span class="text-slate-500">{{ $site->new_submissions_count }} new / {{ $site->submissions_count }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
</x-app-layout>
