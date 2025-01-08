<x-filament-panels::page>
    <livewire:users.expecting-points-card :user="auth()->user()" />
    <div class="flex gap-3 items-stretch">
        <livewire:users.current-points-card :user="auth()->user()" />
    </div>
</x-filament-panels::page>