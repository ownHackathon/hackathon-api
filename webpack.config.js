const path = require('path');

module.exports = {
    entry: './build/js/main.js',
    resolve: {
        alias: {
            app: path.resolve(__dirname,'build/js/app'),
            system: path.resolve(__dirname,'build/js/system'),
        }
    },
    output: {
        filename: 'bundle.js', path: path.resolve(__dirname, 'public/assets/js')
    }
};
