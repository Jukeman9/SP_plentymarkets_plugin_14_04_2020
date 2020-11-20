import {Injectable} from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class BackendService {

  private baseUrl: string = '/sp-data';

  constructor(private httpClient: HttpClient) {
  }

  public postMapping(data) {
    return this.httpClient.post(this.baseUrl, data);
  }

  public getData() {
    return this.httpClient.get(this.baseUrl, {
      headers: {
        "Authorization": "Bearer " + localStorage.getItem('accessToken')
      }
    });
  }
}
