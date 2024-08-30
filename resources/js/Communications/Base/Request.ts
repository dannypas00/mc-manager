import axios, {
  AxiosError,
  AxiosRequestConfig,
  AxiosResponse,
  Method,
} from 'axios';
import { ValidationError } from './ValidationError';
import _ from 'lodash';
import { useToast } from 'vue-toastification';
import I18n from '../../i18n';

export abstract class Request<T, D = Record<string, never>> {
  protected data: D = {} as D;
  protected validationErrors: ValidationError[] = [];
  protected abortController: AbortController = new AbortController();
  public isLoading: boolean = false;
  protected quiet: boolean = false;

  private setValidationErrors(errors: ValidationError[]) {
    // TODO: Implement error store
    this.validationErrors = errors ?? [];

    if (!_.isEmpty(errors) && !this.quiet) {
      useToast().warning(
        I18n.global.t('general.validation_failed') +
          '\n' +
          Object.values(this.serverStore.updateRequest.validationErrors).join(
            ',\n'
          )
      );
    }
  }

  protected abstract getEndPoint(): string;

  protected abstract getMethod(): Method;

  public async getResponse(): Promise<AxiosResponse<T, D>> {
    const method = this.getMethod();

    const useParams = ['GET', 'HEAD', 'OPTION'].includes(method.toUpperCase());

    // Completely kill all possible refs in data tree
    const data = JSON.parse(JSON.stringify(this.data));

    if (this.isLoading) {
      this.cancel('Request overlap');
      this.abortController = new AbortController();
    }

    this.isLoading = true;

    // Reset validation errors
    this.setValidationErrors([]);

    try {
      return await axios.request<T, AxiosResponse<T>, D>({
        url: this.getEndPoint(),
        method,
        data: useParams ? [] : data,
        params: useParams ? data : [],
        withCredentials: true,
        signal: this.abortController.signal,
      } as AxiosRequestConfig<D>);
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

  public cancel(reason?: string) {
    this.abortController.abort(reason);
  }

  public setQuiet(quiet: boolean): this {
    this.quiet = quiet;
    return this;
  }
}
