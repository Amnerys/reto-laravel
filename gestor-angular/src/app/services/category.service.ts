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

  /**
   * Método para crear una categoria por su id (POST)
   */
  createCategory(token,category):Observable<any>{
    let params = this.getJSONParams(category);
    return this._http.post(this.url + 'category', params, {headers: this.headers});
  }

  /**
   * Método para sacar todas las categorías por su id (GET)
   */
  getCategories():Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.get(this.url+'category', {headers: headers});
  }

  /**
   * Método para borrar una categoria por su id (DELETE)
   */
  delete(token,id){
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .set('Authorization', token);
    return this._http.delete(this.url + 'category/' +id, {headers: headers});
  }

  /**
   * Método para actualizar una categoria por su id
   */
  update(token, category, id):Observable<any>{
    let params = this.getJSONParams(category);
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .set('Authorization', token);
    return this._http.put(this.url + 'category/' +id, params, {headers: headers});
  }

  /**
   * Método para sacar una categoria por su id (GET)
   */
  getCategoryDetail(id):Observable<any>{
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.get(this.url + 'category/' + id, {headers: headers});
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
