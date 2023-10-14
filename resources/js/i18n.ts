import { createI18n, I18nOptions } from 'vue-i18n';

import _ from 'lodash';

const translationFiles = import.meta.globEager('../Locale/**/*.json');

const languages = {};

_.each(
  translationFiles,
  (file: { default: Record<string, string> }, path: string) => {
    const translationPath = path
      .replace('../Locale/', '')
      .replace('.json', '')
      .replaceAll('/', '.');

    _.set(languages, translationPath, file.default);
  },
);

const isProduction = import.meta.env.NODE_ENV === 'production';

export default createI18n({
  // Messages contains all the translations
  messages: languages,

  // Set up basic locale settings based on .env
  locale: import.meta.env.VITE_I18N_LOCALE || 'en',
  fallbackLocale: import.meta.env.VITE_I18N_FALLBACK_LOCALE || 'en',

  // Setup silent missing warnings for production environments
  silentFallbackWarn: isProduction,
  silentTranslationWarn: isProduction,

  // Inject the $t() function in all components
  globalInjection: true,

  // Custom error handling for forgotten translations, with checks for production environment
  missing: (locale, path) => {
    if (isProduction) {
      return;
    }

    throw new Error(
      `Missing translation! Language: '${locale}' | Key/Path: '${path}'`,
    );
  },
} as I18nOptions);
