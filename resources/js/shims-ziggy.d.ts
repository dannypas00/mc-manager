import routeFn from 'ziggy-js';
import { TranslateOptions } from 'vue-i18n';

declare global {
  let route: typeof routeFn;
  let $route: typeof routeFn;
  let $t: (key: string, named?: string, options?: TranslateOptions) => string;
}
