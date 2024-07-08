import axios, {
  AxiosError,
  AxiosRequestConfig,
  AxiosResponse,
  Method,
} from 'axios';
import { ValidationError } from './ValidationError';

export abstract class Request<T, D = Record<string, never>> {
  protected data: D = {} as D;
  protected validationErrors: ValidationError[] = [];
  protected abortController: AbortController = new AbortController();

  private setValidationErrors(errors: ValidationError[]) {
    // TODO: Implement error store
    this.validationErrors = errors ?? [];
  }

  protected abstract getEndPoint(): string;

  protected abstract getMethod(): Method;

  public async getResponse(): Promise<AxiosResponse<T, D>> {
    const method = this.getMethod();

    const useParams = ['GET', 'HEAD', 'OPTION'].includes(method.toUpperCase());

    // Completely kill all possible refs in data tree
    const data = JSON.parse(JSON.stringify(this.data));

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
        switch (error.status) {
          case 422:
            this.setValidationErrors(error.response?.data.errors ?? []);
            break;
        }
      }
      throw error;
    }
  }

  public cancel(reason?: never) {
    this.abortController.abort(reason);
  }
}
