const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
        // "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        }
    },

    // darkMode: "class",

    plugins: [
        require('@tailwindcss/forms'),
        // require('flowbite/plugin')
        // require("tw-elements/dist/plugin.cjs")
    ],

    // important: true,
    prefix: 'tw-',

    // content: [
    //     "./src/**/*.{html,js}",
    //     "./node_modules/tw-elements/dist/js/**/*.js"
    //   ],

    // content: [
    //     "./node_modules/flowbite/**/*.js"
    // ]
};
