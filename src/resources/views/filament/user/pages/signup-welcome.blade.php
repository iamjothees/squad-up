<x-filament-panels::page>
    <div class="bg-gray-900 min-h-screen text-white flex flex-col items-center justify-center p-6">
        <!-- Hero Section -->
        <div class="text-center mb-10">
          <h1 class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-green-400 to-teal-500 bg-clip-text text-transparent">
            Welcome to Squad Up!
          </h1>
          <p class="mt-4 text-lg sm:text-xl">
            Where teamwork and rewards come together. Start your journey to earning and growing now!
          </p>
          <a wire:navigate href="{{ route('filament.user.pages.dashboard') }}" class="inline-block mt-6 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg font-semibold shadow-md">
            Explore Dashboard
          </a>
        </div>
      
        <!-- Core Benefits -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center mb-10 max-w-4xl">
          <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
            <h3 class="text-xl font-bold mb-3">💡 Refer & Earn</h3>
            <p>Invite friends and earn rewards for each successful referral.</p>
          </div>
          <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
            <h3 class="text-xl font-bold mb-3">📈 Track Progress</h3>
            <p>Monitor your referral performance and grow your points.</p>
          </div>
          <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
            <h3 class="text-xl font-bold mb-3">💸 Redeem Rewards</h3>
            <p>Convert your points into cash and enjoy your earnings.</p>
          </div>
        </div>

        <!-- Referal Partner Share Info Card -->
        <div class="mb-3">
          <livewire:users.referal-partner-share-info-card />
        </div>
      
        <!-- Gamified Progress Tracker -->
        <div class="w-full max-w-md bg-gray-800 rounded-lg p-6 shadow-lg text-center">
          <h4 class="text-lg font-bold mb-3">Your Progress</h4>
          <div class="bg-gray-700 w-full h-4 rounded-full overflow-hidden">
            <div class="bg-green-500 h-full w-1/3"></div>
          </div>
          <p class="mt-3 text-sm">You’re just getting started—keep referring to reach new milestones!</p>
        </div>
      
        <!-- Footer Note -->
        <div class="mt-10 text-center text-gray-400 text-sm">
          Start sharing and earning now. Your squad is counting on you!
        </div>
    </div>      
</x-filament-panels::page>
