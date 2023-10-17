import routeFn from 'ziggy-js';
import axios, { AxiosRequestConfig, AxiosResponse, Method } from 'axios';

export type ValidationError = Record<string | number, ValidationError | string>;

export abstract class Request<T, D = Record<string, any>> {
  private data: D;
  private validationErrors: ValidationError = {};

  protected abstract getEndPoint (): routeFn;

  protected abstract getMethod (): Method;

  public getResponse (): Promise<AxiosResponse<T, D>> {
    const method = this.getMethod();
    return axios.request<T, AxiosResponse<T>, D>({
      url: this.getEndPoint(),
      method: method,
      data: this.data,
      params: this.data,
    } as AxiosRequestConfig<D>)
      .catch((response: AxiosResponse<T>): AxiosResponse<T> => {
        switch (response.status) {
          case 422:
            this.setValidationErrors(response);
            break;
        }

        return response;
      });
  }

  private setValidationErrors(response: AxiosResponse<T>) {

  }
}
