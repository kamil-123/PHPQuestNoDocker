var Encore = require('@symfony/webpack-encore');

Encore
	.configureBabel(function(babelConfig) {
		babelConfig.presets.push('@babel/react');
		babelConfig.plugins.push('@babel/plugin-proposal-class-properties');
	})
	// directory where compiled assets will be stored
	.setOutputPath('public/build/')
	// public path used by the web server to access the output path
	.setPublicPath('/build')

	.addEntry('app', './assets/js/app.js')
	.addEntry('employee_search', './assets/js/react/entries/employeeSearch.js')
	.addEntry('employee_form', './assets/js/pages/employee/form.js')

	// will require an extra script tag for runtime.js
	// but, you probably want this, unless you're building a single-page app
	.enableSingleRuntimeChunk()

	/*
	 * FEATURE CONFIG
	 *
	 * Enable & configure other features below. For a full
	 * list of features, see:
	 * https://symfony.com/doc/current/frontend.html#adding-more-features
	 */
	.cleanupOutputBeforeBuild()
	.enableBuildNotifications()
	.enableSourceMaps(!Encore.isProduction())
	// enables hashed filenames (e.g. app.abc123.css)
	.enableVersioning(Encore.isProduction())

	// enables Sass/SCSS support
	.enableSassLoader()

	// uncomment if you use TypeScript
	//.enableTypeScriptLoader()

	// uncomment if you're having problems with a jQuery plugin
	//.autoProvidejQuery()

	// uncomment if you use API Platform Admin (composer req api-admin)
	//.enableReactPreset()
	//.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
