<div class="space-y-2 pt-2 pb-3">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>

    <x-nav-link :href="route('settings.security')" :active="request()->routeIs('settings.security')">
        {{ __('Security Settings') }}
    </x-nav-link>
</div>
