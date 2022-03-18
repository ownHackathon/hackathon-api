const {defineConfig} = require('@vue/cli-service');
const path = require("path");

module.exports = defineConfig({
    transpileDependencies: true,
    outputDir: "public/",
    assetsDir: "assets/",
    indexPath: './../public/default.mustache',
    chainWebpack: config => {
        config
            .entry("app")
            .clear()
            .add("./build/main.js")
            .end();
        config.resolve.alias
            .set("@", path.join(__dirname, "build/"))
        config
            .plugin('html')
            .tap(args => {
                args[0].template = 'public/default.tpl';
                args[0].inject = 'body';
                return args
            })
    },
});
