import routeFn from 'ziggy-js';
import { TranslateOptions } from 'vue-i18n';
import { ToastOptions } from 'vue-toastification/dist/types/types';

let ToastFunction: (content: string, options?: ToastOptions) => void;

declare global {
  let route: typeof routeFn;
  let $route: typeof routeFn;
  let $toast: {
    info: typeof ToastFunction,
    success: typeof ToastFunction,
    warning: typeof ToastFunction,
    error: typeof ToastFunction
  }
  let $t: (key: string, named?: unknown, options?: TranslateOptions) => string;
}
