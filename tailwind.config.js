import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    // presets: [preset],

    darkMode: 'class',
    
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './resources/views/livewire/**/*.blade.php', 
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            
            keyframes: {
                fadeIn: {
                    "0%": {opacity: 0},
                    "100%": {opacity: 1},
                },
                fadeOut: {
                    "0%": {opacity: 1},
                    "100%": {opacity: 0},
                },
                scaleIn: {
                    "0%": {transform: "scale(0)"},
                    "100%": {transform: "scale(1)"},
                },
                scaleOut: {
                    "0%": {transform: "scale(1)"},
                    "100%": {transform: "scale(0)"},
                }
            },
            
            animation: {
                fadeIn: "fadeIn 0.5s ease-in forwards",
                fadeOut: "fadeOut 0.5s ease-in forwards",
                scaleIn: "scaleIn 0.3s ease-in forwards",
                scaleOut: "scaleOut 0.3s ease-in forwards"
            },

        },
    },
    plugins: [],
};
