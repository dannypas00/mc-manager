import axios, { AxiosError, AxiosRequestConfig, AxiosResponse, Method } from 'axios';
import { ValidationError } from './ValidationError';
import _ from 'lodash';
import { useToast } from 'vue-toastification';
import I18n from '../../i18n';

export abstract class Request<T, D extends Record<string, any> = Record<string, any>> {
  protected data: D = {} as D;
  protected validationErrors: ValidationError[] = [];
  protected abortController: AbortController = new AbortController();
  public isLoading: boolean = false;
  protected quiet: boolean = false;
  protected headers: Record<string, string> = {};

  private setValidationErrors (errors: ValidationError[]) {
    // TODO: Implement error store
    this.validationErrors = errors ?? [];

    if (!_.isEmpty(errors) && !this.quiet) {
      console.log(_.isObject(errors));
      const header = I18n.global.t('general.validation_failed');
      const message = _.isString(errors)
        ? I18n.global.t(errors)
        : Object.values(errors).join(',\n');
      useToast().warning(`${header}\n${message}`);
    }
  }

  protected abstract getEndPoint (): string;

  protected abstract getMethod (): Method;

  public async getResponse (): Promise<AxiosResponse<T, D>> {
    let method = this.getMethod();

    const useParams = ['GET', 'HEAD', 'OPTION'].includes(method.toUpperCase());

    // Files only come along when formdata is used
    let formData = new FormData();
    _.each(this.data, (value: any, key: string) => {
      if (value === null || value === undefined || value === '') {
        return;
      }

      if (value instanceof File) {
        formData.append(key, value, value.name);
      } else if (typeof value === 'boolean') {
        console.log(key, value);
        formData.append(key, value ? '1' : '0');
      } else {
        formData.append(key, value);
      }
    });

    // Laravel can't accept PUT or PATCH requests for some content types, so we need to set _method
    if (method == 'PUT' || method == 'PATCH') {
      formData.append('_method', method);
      method = 'POST';
    }

    if (this.isLoading) {
      this.cancel('Request overlap');
      this.abortController = new AbortController();
    }

    this.isLoading = true;

    // Reset validation errors
    this.setValidationErrors([]);

    try {
      return await axios.request<T, AxiosResponse<T>>({
        url: this.getEndPoint(),
        method,
        data: formData,
        withCredentials: true,
        signal: this.abortController.signal,
        headers: this.headers,
      } as AxiosRequestConfig);
    } catch (error) {
      if (error instanceof AxiosError) {
        switch (error.response?.status) {
          case 422:
            this.setValidationErrors(error.response?.data.errors ?? []);
            break;
          case 500:
            if (!this.quiet) {
              useToast().error(I18n.global.t('general.server_error'));
            }
            break;
        }
      }

      throw error;
    } finally {
      this.isLoading = false;
    }
  }

  public cancel (reason?: string) {
    this.abortController.abort(reason);
  }

  public setQuiet (quiet: boolean): this {
    this.quiet = quiet;
    return this;
  }
}
