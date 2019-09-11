var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('/js/app', './assets/js/app.js')
    .addEntry('/js/delete-confirmation', './assets/js/delete-confirmation.js')
    .addEntry('/js/mercure-subscribe', './assets/js/mercure-subscribe.js')
    .addEntry('/js/tags', './assets/js/tags.js')
    .addStyleEntry('/css/app', ['./assets/scss/app.scss'])

    .enableBuildNotifications()
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
