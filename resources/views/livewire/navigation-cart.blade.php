<x-nav-link wire:navigate href="{{ route('cart') }}" :active="request()->routeIs('cart')">
    {{ "Cart($this->count)"}}
</x-nav-link>
