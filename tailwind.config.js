module.exports = {
    content: ["./default.mustache", "./build/**/*.{vue,js,ts,jsx,tsx}",],
    theme: {
        fontFamily: {
            'sans': ['ui-sans-serif', 'Segoe UI', 'sans-serif', 'Arial', 'Helvetica'],
        },
        extend: {
            typography: {
                DEFAULT: {
                    css: {
                        color: '#cbd5e1',
                        a: {
                            color: '#e43c5c',
                            '&:hover': {
                                color: '#ea6981',
                            },
                            textDecorationLine: 'none',
                        },
                        h1: {
                            color: '#cbd5e1',
                            fontSize: '2rem',
                        },
                        h2: {
                            color: '#cbd5e1',
                            fontSize: '1.85rem',
                        },
                        h3: {
                            color: '#cbd5e1',
                            fontSize: '1.65rem',
                        },
                        h4: {
                            color: '#cbd5e1',
                            fontSize: '1.45rem',
                        },
                        h5: {
                            color: '#cbd5e1',
                            fontSize: '1.25rem',
                        },
                        h6: {
                            color: '#cbd5e1',
                            fontSize: '1.05rem',
                        },
                    },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ],
};
