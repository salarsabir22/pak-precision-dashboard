@props(['type' => 'success'])

<div role="alert" {{ $attributes->merge(['class' => 'alert '.($type === 'success' ? 'alert-success' : 'alert-info')]) }}>
    <span>{{ $slot }}</span>
</div>
