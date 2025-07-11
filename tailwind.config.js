import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./app/Livewire/**/*Table.php",
        "./app/Livewire/**/*.php",
        "./vendor/power-components/livewire-powergrid/resources/views/**/*.php",
        "./vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php",
    ],
    presets: [
        require("./vendor/power-components/livewire-powergrid/tailwind.config.js"), 
    ],

    darkMode:'class',
    theme: {
        extend: {
            colors: {
                primary: 'var(--color-primary)',
                'primary-lt': 'var(--color-primary-lt)',
                'primary-md': 'var(--color-primary-md)',
                'primary-dk': 'var(--color-primary-dk)',
              },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms],
};
