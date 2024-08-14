import pluginVue from 'eslint-plugin-vue';
import eslintPluginPrettierRecommended from 'eslint-plugin-prettier/recommended';
import tseslint from 'typescript-eslint';

export default [
  ...tseslint.configs.recommended,
  ...pluginVue.configs['flat/strongly-recommended'],
  eslintPluginPrettierRecommended,
  {
    files: ['src/**/*.js', 'src/**/*.ts', 'src/**/*.vue'],
    rules: {
      'no-unused-expressions': 'off',
      'arrow-parens': [1, 'as-needed'],
      'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'semi': [1, 'always'],
      'max-len': [
        process.env.NODE_ENV === 'production' ? 'warn' : 'error',
        {
          code: 120,
          tabWidth: 2,
        },
      ],
      'comma-dangle': ['error', 'always-multiline'],
      // Is recommended to keep this off in Typescript projects
      'no-undef': 'off',
      'vue/max-attributes-per-line': [
        'warn',
        {
          singleline: 2,
          multiline: 1,
        },
      ],
      'vue/component-name-in-template-casing': [
        'error',
        'PascalCase',
        {
          registeredComponentsOnly: true,
          ignores: [],
        },
      ],
      'vue/attributes-order': [
        'error',
        {
          order: [
            'DEFINITION',
            'LIST_RENDERING',
            'CONDITIONALS',
            'RENDER_MODIFIERS',
            'GLOBAL',
            ['UNIQUE', 'SLOT'],
            'TWO_WAY_BINDING',
            'OTHER_DIRECTIVES',
            'ATTR_DYNAMIC',
            'ATTR_STATIC',
            'ATTR_SHORTHAND_BOOL',
            'EVENTS',
            'CONTENT',
          ],
          alphabetical: false,
        },
      ],
      'vue/no-reserved-component-names': [0],
      'vue/html-closing-bracket-spacing': 0,
    },
  },
];
