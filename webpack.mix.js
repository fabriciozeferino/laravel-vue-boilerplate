const mix = require('laravel-mix')

// Amend config in separated file for idea compatibility
const config = require('./webpack.config.js')

mix.webpackConfig(config)

mix.browserSync({
  proxy: process.env.APP_URL,
  notify: true,
})

mix.js('resources/js/app.js', 'public/js').vue()

mix.postCss('resources/css/app.css', 'public/css', [
  require('tailwindcss'),
])

if (mix.inProduction()) {
  mix.version()
}