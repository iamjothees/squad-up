<x-filament::section>
    <div class="relative flex flex-col gap-2">
        Your balance:
        <div class="absolute top-1 right-1">
            <x-filament::icon
                icon="icon-refresh"
                wire:click="refreshPoints"
                wire:poll.30s="refreshPoints"
                wire:target="refreshPoints"
                wire:loading.class="animate-spin"
                class="h-5 w-5 text-gray-500 dark:text-gray-400 cursor-pointer p-0"
            />
        </div>
    
        
        <div> <x-points :points="$this->points" /></div>
        <p class="text-gray-500 text-sm">Can be withdrawn at any time</p>
    </div>
</x-filament::section>