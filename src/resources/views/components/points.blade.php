<span
    {{  $attributes?->merge(['class' => "inline-flex items-center gap-1 rounded-md bg-black px-2 py-1 font-medium text-primary-700 ring-1 ring-inset ring-blue-600 font-mono"]) }}
>
    <x-filament::icon icon="icon-power" class="h-5 w-5 text-gray-400"/>
    {{ Number::format($this->points, locale: 'en_IN') }} 
</span>