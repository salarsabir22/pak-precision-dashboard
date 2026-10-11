@props(['tone' => 'ghost'])

@php
    $tones = [
        'new' => 'badge-warning',
        'in_progress' => 'badge-info',
        'done' => 'badge-success',
        'spam' => 'badge-ghost',
        'ghost' => 'badge-ghost',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'badge '.($tones[$tone] ?? 'badge-ghost')]) }}>{{ $slot }}</span>
