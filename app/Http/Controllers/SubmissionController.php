<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Submission;
use App\Models\SubmissionAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $submissions = Submission::query()
            ->with('site')
            ->withCount('attachments')
            ->when($request->filled('site'), fn ($q) => $q->where('site_id', $request->integer('site')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->string('q').'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('company', 'like', $term)
                        ->orWhere('reference', 'like', $term)
                        ->orWhere('subject', 'like', $term);
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('submissions.index', [
            'submissions' => $submissions,
            'sites' => Site::query()->orderBy('name')->get(),
            'filters' => $request->only(['site', 'type', 'status', 'q']),
        ]);
    }

    public function show(Submission $submission): View
    {
        $submission->load(['site', 'attachments']);
        $submission->markRead();

        return view('submissions.show', compact('submission'));
    }

    public function create(): View
    {
        return view('submissions.create', [
            'sites' => Site::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_id' => ['required', 'exists:sites,id'],
            'type' => ['required', 'in:'.implode(',', Submission::TYPES)],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:200'],
            'company' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:80'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:3000'],
            'part_name' => ['nullable', 'string', 'max:300'],
            'brand' => ['nullable', 'string', 'max:100'],
            'part_number' => ['nullable', 'string', 'max:100'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $submission = Submission::create([
            'site_id' => $data['site_id'],
            'type' => $data['type'],
            'reference' => Submission::makeReference($data['type']),
            'status' => 'new',
            'name' => $data['name'],
            'company' => $data['company'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'country' => $data['country'] ?? null,
            'subject' => $data['subject'] ?? ($data['part_name'] ?? null),
            'message' => $data['message'] ?? null,
            'payload' => [
                'contact' => [
                    'name' => $data['name'],
                    'company' => $data['company'] ?? '',
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? '',
                    'country' => $data['country'] ?? '',
                ],
                'subject' => $data['subject'] ?? '',
                'message' => $data['message'] ?? '',
                'part_name' => $data['part_name'] ?? '',
                'brand' => $data['brand'] ?? '',
                'part_number' => $data['part_number'] ?? '',
                'quantity' => $data['quantity'] ?? null,
            ],
        ]);

        return redirect()->route('submissions.show', $submission)->with('status', 'Submission added.');
    }

    public function update(Request $request, Submission $submission): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Submission::STATUSES)],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $submission->update($data);

        return back()->with('status', 'Changes saved.');
    }

    public function destroy(Submission $submission): RedirectResponse
    {
        $submission->delete();

        return redirect()->route('submissions.index')->with('status', 'Submission deleted.');
    }

    public function download(Submission $submission, SubmissionAttachment $attachment): StreamedResponse
    {
        abort_unless($attachment->submission_id === $submission->id, 404);
        abort_unless(Storage::disk('local')->exists($attachment->path), 404);

        return Storage::disk('local')->download($attachment->path, $attachment->original_name);
    }
}
