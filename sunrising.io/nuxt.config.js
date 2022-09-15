const pkg = require('./package');

const appURL = process.env.SUNRISE_URL || 'https://sunrising.tech';
const isProduction = process.env.NODE_ENV === 'production';

/**
 * Provide list of only production modules
 */
const productionOnlyModules = function() {
  if (!isProduction) return [];

  return [
    // Doc:https://github.com/nuxt-community/modules/tree/master/packages/google-tag-manager
    ['@nuxtjs/google-tag-manager', { id: 'GTM-W9XGD82' }],
  ]
};

module.exports = {
  mode: 'spa',

  /*
  ** Headers of the page
  */
  head: {
    title: pkg.name,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: pkg.description },
    ],
    link: [
      { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicon_32-32.png' },
      { rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicon_16-16.png' },
    ],
  },

  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#fff' },

  /*
  ** Global CSS
  */
  css: [
    { src: '~/static/fonts/stylesheet.css' },
    { src: '~/assets/css/main.scss', lang: 'scss' },
    { src: '~/node_modules/animate.css/animate.min.css' }, // https://github.com/daneden/animate.css
  ],

  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    '~/plugins/sunrise-app',
    '~/plugins/vue-youtube',
    '~/plugins/vue-carousel',
  ],

  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://github.com/nuxt-community/axios-module#usage
    '@nuxtjs/axios',
    // Doc:https://github.com/nuxt-community/modules/tree/master/packages/bulma
    '@nuxtjs/bulma',
  ].concat(productionOnlyModules()),
  /*
  ** Axios module configuration
  */
  axios: {
    // See https://github.com/nuxt-community/axios-module#options
    baseURL: appURL,
  },

  env: {
    appURL,
  },

  /*
  ** Router configuration
  */

  router: {
    scrollBehavior: (to, from, savedPosition) => {
      let position = false;

      if (savedPosition) {
        position = savedPosition;
      }

      const scrollTo = (selector) => {
        const offset = document.querySelector(selector)
          .getBoundingClientRect().top + window.scrollY;
        const headerHeight = 200;

        window.scrollTo({
          behavior: 'smooth',
          left: 0,
          top: offset - headerHeight,
        });
      };

      return new Promise((resolve) => {
        if (to.path === from.path) {
          if (to.hash) {
            scrollTo(to.hash);
          } else {
            window.scrollTo({ behavior: 'smooth', left: 0, top: 0 });
          }
          resolve(position);
        }

        // wait for the out transition to complete (if necessary)
        window.$nuxt.$once('triggerScroll', () => {
          // coords will be used if no selector is provided,
          // or if the selector didn't match any element.
          if (to.hash && document.querySelector(to.hash)) {
            // scroll to anchor by returning the selector
            scrollTo(to.hash);
          }
          resolve(position);
        });
      });
    },
  },

  /*
  ** Build configuration
  */
  build: {
    postcss: {
      preset: {
        features: {
          customProperties: false,
        },
      },
    },

    /*
    ** You can extend webpack config here
    */
    extend(config, ctx) {
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/,
        });
      }
    },
  },
};
