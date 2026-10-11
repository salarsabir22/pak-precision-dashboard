<x-app-layout>
    <x-slot name="header">{{ $submission->reference }}</x-slot>
    <x-slot name="title">{{ $submission->reference }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('submissions.index') }}" class="text-sm text-slate-500 hover:text-ink">← Back to inbox</a>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
        <section class="rounded-2xl bg-white border border-slate-200 p-6 space-y-6">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ $submission->typeLabel() }} · {{ $submission->site->name }}</p>
                <h1 class="mt-1 text-2xl font-semibold">{{ $submission->subject ?: $submission->name }}</h1>
            </div>

            <dl class="grid sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">Name</dt><dd class="font-medium">{{ $submission->name }}</dd></div>
                <div><dt class="text-slate-500">Company</dt><dd class="font-medium">{{ $submission->company ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Email</dt><dd class="font-medium"><a class="underline" href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></dd></div>
                <div><dt class="text-slate-500">Phone</dt><dd class="font-medium">{{ $submission->phone ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Country</dt><dd class="font-medium">{{ $submission->country ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Received</dt><dd class="font-medium">{{ $submission->created_at->format('d M Y H:i') }}</dd></div>
            </dl>

            @if ($submission->message)
                <div>
                    <h2 class="font-semibold mb-2">Message</h2>
                    <div class="whitespace-pre-wrap text-sm leading-6 bg-slate-50 rounded-xl p-4">{{ $submission->message }}</div>
                </div>
            @endif

            @if ($submission->type === 'sourcing')
                <div>
                    <h2 class="font-semibold mb-2">Part details</h2>
                    <dl class="grid sm:grid-cols-2 gap-3 text-sm">
                        <div><dt class="text-slate-500">Part</dt><dd>{{ $submission->payload['part_name'] ?? '—' }}</dd></div>
                        <div><dt class="text-slate-500">Brand</dt><dd>{{ $submission->payload['brand'] ?? '—' }}</dd></div>
                        <div><dt class="text-slate-500">Part number</dt><dd>{{ $submission->payload['part_number'] ?? '—' }}</dd></div>
                        <div><dt class="text-slate-500">Quantity</dt><dd>{{ $submission->payload['quantity'] ?? '—' }}</dd></div>
                        <div><dt class="text-slate-500">Needed by</dt><dd>{{ $submission->payload['deadline'] ?? '—' }}</dd></div>
                    </dl>
                </div>
            @endif

            @if ($submission->type === 'inquiry' && !empty($submission->payload['items']))
                <div>
                    <h2 class="font-semibold mb-2">Requested items</h2>
                    <table class="w-full text-sm">
                        <thead class="text-left text-slate-500">
                            <tr>
                                <th class="py-2">Product</th>
                                <th class="py-2">MPN</th>
                                <th class="py-2">Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($submission->payload['items'] as $item)
                                <tr>
                                    <td class="py-2">
                                        {{ $item['name'] ?? 'Item' }}
                                        @if (!empty($item['note']))
                                            <div class="text-slate-500">{{ $item['note'] }}</div>
                                        @endif
                                    </td>
                                    <td class="py-2">{{ $item['mpn'] ?? '—' }}</td>
                                    <td class="py-2">{{ $item['quantity'] ?? 1 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($submission->attachments->isNotEmpty())
                <div>
                    <h2 class="font-semibold mb-2">Attachments</h2>
                    <ul class="space-y-2 text-sm">
                        @foreach ($submission->attachments as $file)
                            <li>
                                <a class="underline" href="{{ route('submissions.download', [$submission, $file]) }}">{{ $file->original_name }}</a>
                                <span class="text-slate-400">({{ number_format($file->size / 1024, 0) }} KB)</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>

        <aside class="space-y-4">
            @if (auth()->user()->isSuperAdmin())
                <form method="POST" action="{{ route('submissions.update', $submission) }}" class="rounded-2xl bg-white border border-slate-200 p-5 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm focus:border-[#0047bb] focus:outline-none focus:ring-2 focus:ring-[#0047bb]/20">
                            @foreach (\App\Models\Submission::STATUSES as $status)
                                <option value="{{ $status }}" @selected($submission->status === $status)>{{ str_replace('_', ' ', $status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Staff notes</label>
                        <textarea name="notes" rows="5" class="w-full rounded-lg border border-[#c4c8d1] bg-white px-3 py-2 text-sm focus:border-[#0047bb] focus:outline-none focus:ring-2 focus:ring-[#0047bb]/20">{{ old('notes', $submission->notes) }}</textarea>
                    </div>
                    <button class="w-full rounded-lg bg-primary text-white py-2 text-sm">Save</button>
                </form>
                <form method="POST" action="{{ route('submissions.destroy', $submission) }}" onsubmit="return confirm('Delete this submission?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm text-red-700 underline">Delete submission</button>
                </form>
            @else
                <div class="rounded-2xl bg-white border border-slate-200 p-5 space-y-3 text-sm">
                    <div>
                        <p class="text-slate-500">Status</p>
                        <p class="font-medium">{{ $submission->statusLabel() }}</p>
                    </div>
                    @if ($submission->notes)
                        <div>
                            <p class="text-slate-500">Staff notes</p>
                            <p class="whitespace-pre-wrap">{{ $submission->notes }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </aside>
    </div>
</x-app-layout>
