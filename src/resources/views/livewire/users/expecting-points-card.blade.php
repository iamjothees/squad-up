<div x-show="$wire.points" x-cloak class="bg-primary-500 border border-primary-200 rounded-lg p-4">
    <div class="flex flex-col gap-2 h-full justify-center">
        <div class="flex gap-2 items-baseline flex-wrap">
            <x-points :points="$this->points" />
            <div><nobr>Points are on its way 🚀</nobr></div>
        </div>
        <p class="text-gray-200 text-sm">will be credited to your wallet on the project confirmation</p>
    </div>
</div>
