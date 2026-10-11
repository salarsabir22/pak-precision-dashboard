<?php

use App\Http\Controllers\Api\IngestController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/submissions', [IngestController::class, 'store'])
    ->middleware(['site.key', 'throttle:120,1'])
    ->name('api.submissions.store');
