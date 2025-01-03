<x-filament::section x-show="$wire.points > 0">
    Your balance:
    <div> <x-points :points="$this->points" /></div>
    <p class="text-gray-500 text-sm">Can be withdrawn at any time</p>
</x-filament::section>