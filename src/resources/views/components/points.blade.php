<span class="inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-1 font-medium text-green-700 ring-1 ring-inset ring-green-600/20 font-mono">
    <x-filament::icon icon="icon-power" class="h-5 w-5 text-gray-400"/>
    {{ Number::format($this->points, locale: 'en_IN') }} 
</span>