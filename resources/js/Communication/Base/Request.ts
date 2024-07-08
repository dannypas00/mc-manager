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

  private setValidationErrors(
    response: AxiosResponse<T & { errors?: ValidationError[] }>
  ) {
    // TODO: Implement error store
    this.validationErrors = response.data.errors ?? [];
  }

  protected abstract getEndPoint(): string;

  protected abstract getMethod(): Method;

  public async getResponse(): AxiosResponse<T, D> {
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
    } catch (error: AxiosError<T, D>) {
      switch (error.status) {
        case 422:
          this.setValidationErrors(error);
          break;
      }
      console.log(error);
      throw error;
    }
  }

  public cancel(reason?: never) {
    this.abortController.abort(reason);
  }
}
