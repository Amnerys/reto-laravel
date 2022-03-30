import {Injectable} from "@angular/core";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Observable} from "rxjs";
import {global} from "./global";
import {Product} from "../models/product";

@Injectable()
export class ProductService {
  public url: string;
  public identity;
  public token;
  public product;
  public headers;

  constructor(
    public _http: HttpClient
  ) {
    this.url = global.url;
    this.product = new Product(1,'', '', '', 1, 0, '' );
    this.headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .set('Authorization', this.token);
    }

  pruebas(){
    return "Lanzando servico de producto";
  }

  /**
   * Método para crear un nuevo producto (POST)
   */
  create(token, product):Observable<any>{
    let params = this.getJSONParams(product);
    console.log(params);
    console.log(token);
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .set('Authorization', token);
    return this._http.post(this.url + 'product', params, {headers: headers});
  }

  /**
   * Método para sacar todos los productos (GET)
   */
  getProducts():Observable<any>{
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.get(this.url + 'product', {headers: headers});
  }

  /**
   * Método para sacar un producto por su id (GET)
   */
  getProductDetail(id):Observable<any>{
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.get(this.url + 'product/' +id, {headers: headers});
  }

  /**
   * Método para actualizar un producto por su id (PUT)
   */
  update(token, product, id):Observable<any>{
    let params = this.getJSONParams(product);
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .set('Authorization', token);
    return this._http.put(this.url + 'product/' +id, params, {headers: headers});
  }

  /**
   * Método para borrar un producto por su id (PUT)
   */
  delete(token,id){
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .set('Authorization', token);
    return this._http.delete(this.url + 'product/' +id, {headers: headers});
  }

  getJSONParams(product){
    let json = JSON.stringify(product);
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


