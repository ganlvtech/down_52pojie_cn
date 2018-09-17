module.exports = {
    root: true,
    env: {
        node: true
    },
    extends: [
        'plugin:vue/recommended'
    ],
    rules: {
        'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'vue/html-indent': ['off'],
        'vue/max-attributes-per-line': ['off']
    },
    parserOptions: {
        parser: 'babel-eslint'
    }
};
