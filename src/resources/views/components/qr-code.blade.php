@assets() @vite(['resources/js/app.js']) @endassets
<div class="max-w-96 aspect-square mx-auto cursor-pointer">
    <img x-data="qrCodeComponent" class="w-full"></img>
</div>
@script
    <script>
        Alpine.data('qrCodeComponent', () => ({
            init(){
                const value = "{{ $value }}";

                var typeNumber = 4;
                var errorCorrectionLevel = 'L';
                var qr = qrcode(typeNumber, errorCorrectionLevel);
                qr.addData(value);
                qr.make();
                this.$el.src = qr.createDataURL(8);
            }
        }))

        Alpine.magic('clipboard', () => {
            return subject => navigator.clipboard.writeText(subject)
        })
    </script>
@endscript