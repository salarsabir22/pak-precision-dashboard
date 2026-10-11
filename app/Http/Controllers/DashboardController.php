<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Submission;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $base = Submission::query();

        return view('dashboard', [
            'newCount' => (clone $base)->where('status', 'new')->count(),
            'openCount' => (clone $base)->where('status', 'in_progress')->count(),
            'doneToday' => (clone $base)->where('status', 'done')->whereDate('updated_at', today())->count(),
            'siteCount' => Site::query()->where('is_active', true)->count(),
            'byType' => [
                'contact' => (clone $base)->where('type', 'contact')->count(),
                'sourcing' => (clone $base)->where('type', 'sourcing')->count(),
                'inquiry' => (clone $base)->where('type', 'inquiry')->count(),
            ],
            'recent' => Submission::query()->with('site')->latest()->limit(8)->get(),
            'bySite' => Site::query()
                ->withCount([
                    'submissions',
                    'submissions as new_submissions_count' => fn ($q) => $q->where('status', 'new'),
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
