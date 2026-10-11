@props(['body' => true])

<div {{ $attributes->merge(['class' => 'card bg-base-100 border border-base-300 shadow-sm']) }}>
    @if ($body)
        <div class="card-body">{{ $slot }}</div>
    @else
        {{ $slot }}
    @endif
</div>
