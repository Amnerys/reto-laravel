import { Component, OnInit } from '@angular/core';
import {User} from "../../models/user";
import {UserService} from "../../services/user.service";
import { Router, ActivatedRoute, Params } from "@angular/router";

@Component({
  selector: 'login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [UserService]
})
export class LoginComponent implements OnInit {
  public page_title: string;
  public user: User;
  public token;
  public status: string;
  public identity;

  constructor(
    private _userService: UserService,
    private _router: Router,
    private _route: ActivatedRoute
  ) {
    this.page_title = 'Identifícate';
    this.user = new User(1,'', '', '', '', '', '');
  }

  ngOnInit(): void {
    //Se ejecuta siempre que cargue el componente y cierra sesión cuando le llega el parámetro 'sure' por URL
    this.logout();
  }

  /**
   * Método que implementa el botón de login del formulario
   */
  onSubmit(form){
    //Recogemos los datos del formulario de registro y los enviamos al back con el userService
    this._userService.signIn(this.user).subscribe(

      response => {
        //Nos devuelve el token
        if (response.status != "error"){
          this.status = "success";
          this.token = response;

          //Objeto del usuario identificado
          this._userService.signIn(this.user, true).subscribe(
            response => {
              this.identity = response;
              //Persistir datos de usuario identificado
              console.log(this.token);
              console.log(this.identity);
              localStorage.setItem('token', this.token);
              localStorage.setItem('identity', JSON.stringify(this.identity));
              //Redirección a la página de inicio
              this._router.navigate(['inicio']);
            },
            error => {
              this.status = "error";
              console.log(<any>error);
            }
          );
          form.reset();
        }else{
          this.status = "error";
        }
      },
      error => {
        console.log(<any>error);
      }
    );
  }

  logout(){
    this._route.params.subscribe(params => {
      let logout = +params ['sure']; //casteamos a int el string llegado
      //Si está logueado borramos datos del almacenamiento local
      if (logout == 1){
        localStorage.removeItem('identity');
        localStorage.removeItem('token');

        this.identity = null;
        this.token = null;

        //Redirección a la página de inicio
        this._router.navigate(['inicio']);
      }
    });
  }

}
