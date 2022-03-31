const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: ["./default.mustache", "./build/**/*.{vue,js,ts,jsx,tsx}",],
    theme: {
        extend: {
            fontFamily: {
                'sans': ['Helvetica', 'Arial', 'sans-serif', ...defaultTheme.fontFamily.sans],
            }
        },
    }, plugins: [],
};
