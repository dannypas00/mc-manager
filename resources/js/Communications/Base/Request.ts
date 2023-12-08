import axios, { AxiosRequestConfig, AxiosResponse, Method } from 'axios';
import { ValidationError } from './ValidationError';

export abstract class Request<T, D = Record<string, never>> {
  protected data: D = {} as D;
  protected validationErrors: ValidationError[] = [];

  private setValidationErrors (response: AxiosResponse<T & { errors?: ValidationError[] }>) {
    // TODO: Implement error store
    this.validationErrors = response.data.errors ?? [];
  }

  protected abstract getEndPoint (): string;

  protected abstract getMethod (): Method;

  public getResponse (): Promise<AxiosResponse<T, D>> {
    const method = this.getMethod();

    const useParams = ['GET', 'HEAD', 'OPTION'].includes(method.toUpperCase());

    return axios.request<T, AxiosResponse<T>, D>({
      url: this.getEndPoint(),
      method,
      data: useParams ? [] : this.data,
      params: useParams ? this.data : [],
    } as AxiosRequestConfig<D>)
      .catch((response: AxiosResponse<T>): AxiosResponse<T> => {
        switch (response.status) {
          case 422:
            this.setValidationErrors(response);
            break;
        }

        // TODO: Implement other default responses

        return response;
      });
  }
}
