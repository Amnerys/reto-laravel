import {Injectable} from "@angular/core";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Observable} from "rxjs";
import {global} from "./global";
import {Category} from "../models/category";

@Injectable()
export class CategoryService {
  public url: string;
  public identity;
  public token;
  public category;
  public headers;

  constructor(
    public _http: HttpClient
  ) {
    this.url = global.url;
    this.category = new Category(1,'','');
    this.token = this.getToken();
    this.headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .set('Authorization',this.token);
  }

  createCategory(token,category):Observable<any>{
    let params = this.getJSONParams(category);
    return this._http.post(this.url + 'category', params, {headers: this.headers});
  }

  getCategories():Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.get(this.url+'category', {headers: headers});
  }

  getJSONParams(category){
    let json = JSON.stringify(category);
    return 'json=' + json;
  }

  /**
   * Método para sacar el token del usuario del local storage
   */
  getToken(){
    let token = localStorage.getItem('token');
    if (token && token != "undefined"){
      this.token = token;
    }else{
      this.token = null;
    }
    return this.token;
  }

}
