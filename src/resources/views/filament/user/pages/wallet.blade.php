<x-filament-panels::page>
    <div class="flex gap-3 items-stretch">
        <livewire:users.current-points-card :user="auth()->user()" />
        <livewire:users.expecting-points-card :user="auth()->user()" />
    </div>
</x-filament-panels::page>