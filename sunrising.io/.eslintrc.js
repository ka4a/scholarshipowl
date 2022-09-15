module.exports = {
  root: true,

  env: {
    browser: true,
  },

  parserOptions: {
    parser: 'babel-eslint'
  },

  extends: [
    'plugin:vue/recommended',
    // 'plugin:prettier/recommended',
    'airbnb-base',
  ],

  // required to lint *.vue files
  plugins: [
    'vue',
    'prettier'
  ],

  // add your custom rules here
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',

		'max-len': ['error', 120],
		'no-shadow': ['error', {
			'allow': ['state', 'getters'],
		}],
    'vue/max-attributes-per-line': 'off',

		// don't require .vue extension when importing
    'import/extensions': ['error', 'always', {
      js: 'never',
      vue: 'never'
    }],

    // disallow reassignment of function parameters
    // disallow parameter object manipulation except for specific exclusions
    'no-param-reassign': ['error', {
      props: true,
      ignorePropertyModificationsFor: [
        'state', // for vuex state
        'acc', // for reduce accumulators
        'e' // for e.returnvalue
      ]
    }],
  },

	settings: {
		'import/core-modules': ['vue', 'vuex'],
		'import/resolver': {
			nuxt: {
				extensions: ['.js', '.vue'],
			},
		}
	}
}
