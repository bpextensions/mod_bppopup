var Encore = require('@symfony/webpack-encore');

// Module build configuration
Encore
    .setOutputPath('modules/mod_bppopup/assets')
    .setPublicPath('/modules/mod_bppopup/assets/')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSassLoader()
    .enableVersioning(Encore.isProduction())
    .disableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction())
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    .addExternals({
        jquery: 'jQuery',
        joomla: 'Joomla',
    })
    .addEntry('module', [
        './modules/mod_bppopup/.dev/js/module.js',
        './modules/mod_bppopup/.dev/scss/module.scss',
    ])
    .configureFilenames({
        css: '[name]-[hash:6].css',
        js: '[name]-[hash:6].js'
    });

// Export configurations
module.exports = Encore.getWebpackConfig();