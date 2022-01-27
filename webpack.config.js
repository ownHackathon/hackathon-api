const path = require('path');

module.exports = {
    entry: './build/js/main.js',
	output: {
        filename: 'bundle.js', path: path.resolve(__dirname, 'public/assets/js')
    }
};
