module.exports = {
  parser: 'babel-eslint',
  globals: {
    wp: true
  },
  env: {
    node: true,
    es6: true,
    amd: true,
    browser: true,
    jquery: true
  },
  extends: ['airbnb'],
  plugins: ['react', 'jsx-a11y', 'import'],
  rules: {
    'react/no-multi-comp': 'off',
    'react/prop-types': 'off',
    'comma-dangle': ['warn', 'never'],
    'no-underscore-dangle': 'off',
    'function-paren-newline': 'off',
    'arrow-parens': ['error', 'as-needed'],
    'object-curly-newline': 'off',
    'import/no-extraneous-dependencies': 'off',
    'import/no-webpack-loader-syntax': 'off',
    'import/prefer-default-export': 'off',
    'react/jsx-filename-extension': ['warn', { extensions: ['.js', '.jsx'] }],
    'jsx-a11y/anchor-is-valid': [
      'error',
      {
        components: ['Link'],
        specialLink: ['to'],
        aspects: ['noHref', 'invalidHref', 'preferButton']
      }
    ],
    'jsx-a11y/label-has-for': [
      'error',
      {
        allowChildren: true
      }
    ]
  },
  settings: {
    'import/resolver': 'webpack'
  }
};
