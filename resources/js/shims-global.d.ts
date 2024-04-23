import { RouteParams, Router } from "ziggy-js";
import { TranslateOptions } from "vue-i18n";
import { ToastOptions } from "vue-toastification/dist/types/types";

let ToastFunction: (content: string, options?: ToastOptions) => void;

declare global {
  let route: (name?: string, params?: RouteParams) => Router | string;
  let $route: (name?: string, params?: RouteParams) => Router | string;
  let $toast: {
    info: typeof ToastFunction;
    success: typeof ToastFunction;
    warning: typeof ToastFunction;
    error: typeof ToastFunction;
  };
  let $t: (key: string, named?: unknown, options?: TranslateOptions) => string;
}
