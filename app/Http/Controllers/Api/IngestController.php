<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class IngestController extends Controller
{
    private const ALLOWED_EXTENSIONS = [
        'pdf', 'jpg', 'jpeg', 'png', 'webp', 'xls', 'xlsx', 'csv', 'doc', 'docx',
        'dwg', 'dxf', 'step', 'stp', 'zip',
    ];

    public function store(Request $request): JsonResponse
    {
        /** @var Site $site */
        $site = $request->attributes->get('site');
        $type = $this->normalizeType($this->value($request, 'type'));

        if (! in_array($type, Submission::TYPES, true)) {
            throw ValidationException::withMessages([
                'type' => 'type must be contact, sourcing, or inquiry.',
            ]);
        }

        $name = $this->value($request, 'name');
        $email = $this->value($request, 'email');

        $errors = [];
        if (mb_strlen($name) < 2) {
            $errors['name'] = 'Please enter a name.';
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        $message = $this->value($request, 'message');
        $subject = $this->value($request, 'subject');
        $payload = [
            'contact' => [
                'name' => $name,
                'company' => $this->value($request, 'company'),
                'email' => $email,
                'phone' => $this->value($request, 'phone'),
                'country' => $this->value($request, 'country'),
            ],
        ];

        if ($type === 'contact') {
            if (mb_strlen($message) < 10) {
                $errors['message'] = 'Please include a message of at least 10 characters.';
            }
            $payload['subject'] = $subject;
            $payload['message'] = $message;
        }

        if ($type === 'sourcing') {
            $partName = $this->value($request, 'part_name', 'partName');
            if (mb_strlen($partName) < 2) {
                $errors['part_name'] = 'Please describe the part you need.';
            }
            $payload['part_name'] = $partName;
            $payload['brand'] = $this->value($request, 'brand');
            $payload['part_number'] = $this->value($request, 'part_number', 'partNumber');
            $payload['quantity'] = $this->intValue($request, 'quantity') ?? 1;
            $payload['deadline'] = $this->value($request, 'deadline');
            $payload['details'] = $this->value($request, 'details', 'message');
            if ($payload['details'] !== '') {
                $message = $payload['details'];
            }
            $subject = $subject !== '' ? $subject : $partName;
        }

        if ($type === 'inquiry') {
            $items = $this->items($request);
            if ($items === []) {
                $errors['items'] = 'Add at least one product to the quote.';
            }
            $payload['items'] = $items;
            $payload['message'] = $message;
        }

        $files = $this->files($request);
        if ($fileError = $this->validateFiles($files)) {
            $errors['files'] = $fileError;
        }

        if ($errors !== []) {
            return response()->json([
                'ok' => false,
                'message' => 'Please check the highlighted fields.',
                'errors' => $errors,
            ], 422);
        }

        $reference = $this->value($request, 'reference') ?: Submission::makeReference($type);

        $submission = DB::transaction(function () use ($request, $site, $type, $reference, $name, $email, $subject, $message, $payload, $files) {
            $submission = Submission::create([
                'site_id' => $site->id,
                'type' => $type,
                'reference' => $reference,
                'status' => 'new',
                'name' => $name,
                'company' => $this->value($request, 'company') ?: null,
                'email' => $email,
                'phone' => $this->value($request, 'phone') ?: null,
                'country' => $this->value($request, 'country') ?: null,
                'subject' => $subject ?: null,
                'message' => $message ?: null,
                'payload' => $payload,
                'ip' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
            ]);

            foreach ($files as $file) {
                $stored = $file->store('attachments/'.$site->id.'/'.$submission->id, 'local');
                $submission->attachments()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $stored,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }

            return $submission;
        });

        return response()->json([
            'ok' => true,
            'reference' => $submission->reference,
            'id' => $submission->id,
        ], 201);
    }

    private function normalizeType(string $type): string
    {
        $type = strtolower(trim($type));

        return match ($type) {
            'msg', 'message', 'contact' => 'contact',
            'src', 'source', 'sourcing' => 'sourcing',
            'rfq', 'quote', 'inquiry' => 'inquiry',
            default => $type,
        };
    }

    private function value(Request $request, string ...$keys): string
    {
        foreach ($keys as $key) {
            $value = $request->input($key);
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return '';
    }

    private function intValue(Request $request, string $key): ?int
    {
        $value = $request->input($key);
        if ($value === null || $value === '') {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
    }

    private function items(Request $request): array
    {
        $raw = $request->input('items', []);
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : [];
        }
        if (! is_array($raw)) {
            return [];
        }

        $items = [];
        foreach (array_slice($raw, 0, 50) as $row) {
            if (! is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? $row['slug'] ?? ''));
            if ($name === '') {
                continue;
            }
            $items[] = [
                'slug' => $row['slug'] ?? null,
                'name' => $name,
                'sku' => $row['sku'] ?? null,
                'mpn' => $row['mpn'] ?? null,
                'brand' => $row['brand'] ?? null,
                'quantity' => (int) ($row['quantity'] ?? 1) ?: 1,
                'note' => $row['note'] ?? null,
            ];
        }

        return $items;
    }

    private function files(Request $request): array
    {
        $files = array_merge(
            $request->file('files', []),
            $request->file('attachments', [])
        );

        if (! is_array($files)) {
            $files = $files ? [$files] : [];
        }

        return array_values(array_filter($files, fn ($file) => $file && $file->isValid() && $file->getSize() > 0));
    }

    private function validateFiles(array $files): ?string
    {
        if (count($files) > 5) {
            return 'Attach at most 5 files.';
        }

        $total = 0;
        foreach ($files as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (! in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
                return '"'.$file->getClientOriginalName().'" is not an accepted file type.';
            }
            if ($file->getSize() > 10 * 1024 * 1024) {
                return '"'.$file->getClientOriginalName().'" is larger than 10 MB.';
            }
            $total += $file->getSize();
        }

        if ($total > 20 * 1024 * 1024) {
            return 'Attachments are larger than 20 MB in total.';
        }

        return null;
    }
}
