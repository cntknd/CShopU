import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Ensure this path covers your navigation bar file
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // ADD THE CUSTOM MAROON COLOR PALETTE HERE
            colors: {
                maroon: {
                    50: '#F5E6E6',
                    100: '#E6CCCC',
                    200: '#CC9999',
                    300: '#B36666',
                    400: '#993333',
                    500: '#800000', // Primary maroon
                    600: '#6D0000', // Darker for navbar background
                    700: '#5A0000', // Darkest for border
                    800: '#470000',
                    900: '#330000',
                },
            },
            
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};