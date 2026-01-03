@props(['href' => url()->previous()])

<x-apollo-button
    :href="$href"
    color="secondary"
    {{ $attributes->merge(['class' => 'px-4 fw-bold shadow-sm']) }}>
    Cancel
</x-apollo-button>
