<a href="https://wa.me/{{ config('app.admin.whatsapp') }}?text={{ urlencode("Hi Admin, \nName:{$user->name}") }}"
    class="fixed bottom-6 right-6 bg-green-600 hover:bg-green-700 text-white h-14 w-14 rounded-full shadow-lg flex items-center justify-center transition-transform transform hover:scale-105 z-50"
    target="_blank" rel="noopener noreferrer" title="Contact Admin on WhatsApp"
>
    <x-filament::icon icon="icon-whatsapp" class="h-10 w-10 -translate-y-0.5 translate-x-0.5" />
</a>
