import preset from './vendor/filament/support/tailwind.config.preset'
import daisyui from "daisyui"
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    plugins: [
        daisyui,
      ],
}
