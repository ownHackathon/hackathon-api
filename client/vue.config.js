const {defineConfig} = require('@vue/cli-service');
const path = require("path");

module.exports = defineConfig({
    transpileDependencies: true,
    outputDir: "./../public/",
    assetsDir: "assets/",
    chainWebpack: config => {
        config
            .entry("app")
            .clear()
            .add("./src/main.js")
            .end();
        config.resolve.alias
            .set("@", path.join(__dirname, "src/"))
        config
            .plugin('html')
            .tap(args => {
                args[0].template = 'src/index.tpl';
                args[0].inject = 'body';
                return args
            })
    },
});
