@assets() @vite(['resources/js/app.js']) @endassets
<x-filament-panels::page>
    <div 
        x-show="$wire.verifiedUser" x-cloak
        class="max-w-96 aspect-square mx-auto cursor-pointer" 
    >
        <img x-data="qrCodeComponent" class="w-full"></img>

        <div class="mt-3 text-center">
            {{ $this->infolist }}
        </div>
    </div>

    <div x-show="!$wire.verifiedUser" x-cloak class="bg-gray-900 text-white py-8 px-6 rounded-lg shadow-lg max-w-4xl mx-auto mt-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-center">
            Get Verified & Start Earning!
        </h2>
        <p class="mt-4 text-center text-lg">
            Your journey to earning rewards starts here. Contact our admin to verify your account and begin referring friends today!
        </p>
        
        <!-- Contact Options -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mt-6">
            <!-- WhatsApp -->
            <a href="https://wa.me/{{ config('app.admin.whatsapp') }}?text={{urlencode("Hi Admin, I would like to get verified as a referral partner on Squad Up. My details are: \n Name: {$user->name}\n Phone: +{$user->phone}")}}" target="_blank"
                class="flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow-md transition"
            >
            <x-filament::icon icon="icon-whatsapp" class="h-5 w-5 inline" />
            Contact via WhatsApp
            </a>
        
            <!-- Email -->
            <a href="mailto:{{ config('app.admin.email') }}?subject=Request for Referral Partner Verification&body=Hi Admin, I would like to get verified as a referral partner on Squad Up. My details are:  Name: {{$user->name}} Phone: +{{$user->phone}}"
                class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow-md transition"
            >
            <x-filament::icon icon="icon-email" class="h-5 w-5 inline" />
            Email the Admin
            </a>
        </div>
        
        <!-- Motivational Note -->
        <p class="mt-6 text-center text-sm text-gray-400">
            Once you're verified, you'll unlock the power of referrals and rewards. Let's make it happen!
        </p>
    </div>

</x-filament-panels::page>
@script
    <script>
        Alpine.data('qrCodeComponent', () => ({
            init(){
                const referalPartnerCode = "{{ $user->referal_partner_code }}";

                var typeNumber = 4;
                var errorCorrectionLevel = 'L';
                var qr = qrcode(typeNumber, errorCorrectionLevel);
                qr.addData(referalPartnerCode);
                qr.make();
                this.$el.src = qr.createDataURL(8);
            }
        }))

        Alpine.magic('clipboard', () => {
            return subject => navigator.clipboard.writeText(subject)
        })
    </script>
@endscript