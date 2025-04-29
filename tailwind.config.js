import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito',...defaultTheme.fontFamily.sans],
            },
            colors: {
                gray: colors.gray,
                blue: colors.sky,
                red: colors.rose,
                pink: colors.fuchsia,
                yellow: colors.amber,
                green: colors.emerald,
                purple: colors.violet,
                indigo: colors.indigo,
                teal: colors.teal,
                cyan: colors.cyan,
                white: colors.white,
                black: colors.black,
                transparent: colors.transparent,
                ghostWhite: '#f8f9fc', // blanco casi fantasmal 👻✨
                royalBlue: '#264ec1', // azul real 👑 💙
            },
        },
    },

    plugins: [forms, typography],
};
