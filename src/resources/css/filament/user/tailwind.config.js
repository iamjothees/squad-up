import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/User/**/*.php',
        './resources/views/components/user/**/*.blade.php',
        './resources/views/livewire/users/**/*.blade.php',
        './resources/views/filament/user/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/codewithdennis/filament-simple-alert/resources/**/*.blade.php',
    ],
}
