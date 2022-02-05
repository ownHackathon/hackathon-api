const path = require('path');

module.exports = {
    entry: './build/js/main.js',
    resolve: {
        alias: {
            App: path.resolve(__dirname,'build/js/App'),
            System: path.resolve(__dirname,'build/js/System'),
        }
    },
    output: {
        filename: 'bundle.js', path: path.resolve(__dirname, 'public/assets/js')
    }
};
