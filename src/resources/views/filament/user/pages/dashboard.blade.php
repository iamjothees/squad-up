<x-filament-panels::page>
  <div x-data="{ closedBanners: @json(session('closed_banners', []))}" >
    {{-- Motto Banner --}}
    <div x-show="!closedBanners.includes($el.id)" id="motto" class="relative bg-gradient-to-r from-[#004d00] via-[#005c00] to-[#006600] text-white p-6 rounded-lg shadow-lg max-w-full mx-auto mt-5">
      <button
        wire:click="closeBanner('motto')"
        x-on:click="$el.parentElement.classList.add('hidden')"
        class="absolute top-2 right-2 text-white text-xl hover:text-gray-300 transition"
       >
        &times;
      </button>
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center">
        Refer projects, earn points, and <a href="{{ route('filament.user.pages.wallet') }}" class="underline">cash out and enjoy your share</a>!
      </h1>
      <p class="mt-4 text-base sm:text-lg text-center">
        It's simple, fun, and rewarding. Start sharing today!
      </p>
    </div>
  </div>
  
  <x-filament::section>
    <div class="flex items-center gap-2">
      <span class="text-sm">
        <x-filament::icon icon="icon-confette" color="#FFD700" class="h-5 w-5 inline" />
        Your shares will be credited as soon as the project gets the green light. 🚀
      </span>
    </div>
  </x-filament::section>

  <livewire:users.referal-partner-share-info-card />

</x-filament-panels::page>
