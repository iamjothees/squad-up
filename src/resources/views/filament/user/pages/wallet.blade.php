<x-filament-panels::page>
    <div class="flex flex-col-reverse md:flex-row gap-2">
        <div class="min-w-96 max-w-96 flex flex-col gap-2">
            <livewire:users.current-points-card :user="auth()->user()" />
            {{ $this->requestToRedeemAction }}
        </div>
        <div class="flex-grow">
            <livewire:users.expecting-points-card :user="auth()->user()" />
        </div>
    </div>

    <livewire:users.redeems :user="auth()->user()" />
</x-filament-panels::page>