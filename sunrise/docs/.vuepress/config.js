const SUNRISE_URL = 'http://localhost:9000';

require('prismjs');
require('prism-json-fold');

module.exports = {
  base: '/docs/',
  title: 'Sunrise Documentation',

  markdown: {
    linkify: true,
    config: (md) => {
      // console.log('md', md);
      // use more markdown-it plugins!
      // md.use({ apply: () => {
      //   console.log('md', md)
      //   require('prism-json-fold')
      // }});
    }
  },

  themeConfig: {
    logo: '/logo.svg',
    sidebar: {
      '/api/': [
        {
          title: 'REST API',
          collapsable: false,
          children: [
            'get-started',
            'scholarships',
          ]
        },
        {
          title: 'Entities',
          collapsable: false,
          children: [
            'entity/field',
            // 'entity/requirement',
            'entity/scholarship',
            'entity/scholarship_field',
            // 'entity/scholarship_requirement',
            // 'entity/scholarship_template',
            // 'entity/scholarship_content',
            // 'entity/scholarship_winner',
            'entity/application',
            'entity/application_status',
          ]
        }
      ],
    },
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/guide/' },
      { text: 'Concepts', link: '/concepts/' },
      { text: 'API', link: '/api/' },
      { text: 'Scholarship.App', link: 'https://app.scholarship.app' },
    ]
  },

  configureWebpack: (config, isServer) => {
    if (!isServer) {
      // mutate the config for client
      // console.log('config', config);
    }
  }
}
