@assets() @vite(['resources/js/app.js']) @endassets
<x-filament-panels::page>
    <div 
        x-on:click="$clipboard(`${$wire.get('user')->referal_partner_code}`)"
        class="max-w-96 aspect-square mx-auto cursor-pointer" 
    >
        <img x-data="qrCodeComponent" class="w-full"></img>

        <div class="mt-3 text-center">
            {{ $this->infolist }}
        </div>
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