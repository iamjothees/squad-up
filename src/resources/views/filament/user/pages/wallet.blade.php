<x-filament-panels::page>
    <livewire:users.expecting-points-card :user="auth()->user()" />
    <div class="max-w-96 flex flex-col gap-2">
        <livewire:users.current-points-card :user="auth()->user()" />
        {{ $this->requestToRedeemAction }}
    </div>

    <livewire:users.redeems :user="auth()->user()" />
</x-filament-panels::page>