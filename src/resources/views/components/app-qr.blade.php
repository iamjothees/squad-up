<div x-data x-on:click=" $clipboard( `{{ config('app.url') }}` ) " {{ $attributes->merge(['class' => "flex flex-col items-center justify-center"]) }}>
    <div class="border-4 border-white rounded-lg shadow-md">
        <x-qr-code :value="config('app.url')" />
    </div>
    <div compact class="p-2">
        <div class="mt-2 flex items-center gap-2">
            <span class="font-sans font-semibold">{{ config('app.url') }}</span>
            <x-filament::icon icon="icon-clipboard" class="h-5 w-5 text-white cursor-pointer" />
        </div>
    </div>
</div>

@script
    <script>
        Alpine.magic('clipboard', () => {
            return subject => navigator.clipboard.writeText(subject)
        })
    </script>
@endscript