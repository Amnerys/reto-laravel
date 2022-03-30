import {Injectable} from "@angular/core";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Observable} from "rxjs";
import {global} from "./global";

@Injectable()
export class UserService{
  public url: string;
  public identity;
  public token;

  constructor(
    public _http: HttpClient
  ) {
    this.url = global.url;
  }

  test(){
    return "Hola mundo desde service";
  }

  /**
   * Método para registrar usuarios mandando por JSON los datos al BackEnd
   */
  register(user): Observable<any>{
    let params = this.getJSONParams(user);
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

    return this._http.post(this.url+'register', params, {headers: headers});
  }

  /**
   * Método para loguear usuarios mandando por JSON los datos al BackEnd
   */
  signIn(user, gettoken = null): Observable<any>{
    if(gettoken != null){
      user.gettoken = 'true';
    }
    let params = this.getJSONParams(user);
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

    return this._http.post(this.url+'login', params, {headers: headers});
  }

  getJSONParams(user){
    let json = JSON.stringify(user);
    return 'json=' + json;
  }

  update(token, user) : Observable<any>{
    //Coger datos de user y convertirlos a jsonString
    let params = this.getJSONParams(user);
    console.log('Parámetros: ' + typeof (params) + ' Token: ' +typeof (token));
    let headers = new HttpHeaders()
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .set('Authorization',token);

    return this._http.put(this.url+'user/update', params, {headers: headers});
  }

  /**
   * Método para sacar la identidad del usuario del local storage
   */
  getIdentity(){
    let identity = JSON.parse(localStorage.getItem('identity'));
    if (identity && identity != "undefined"){
      this.identity = identity;
    }else{
      this.identity = null;
    }
    return this.identity;
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
