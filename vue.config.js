const {defineConfig} = require('@vue/cli-service');
const path = require("path");

module.exports = defineConfig({
    transpileDependencies: true,
    outputDir: "public/",
    assetsDir: "assets/",
    indexPath: './../template/layout/default.mustache',
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
                args[0].template = 'template/layout/default.tpl';
                args[0].inject = 'body';
                return args
            })
    },
});
