import { Component, OnInit, DoCheck } from '@angular/core';
import { UserService } from "./services/user.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [UserService]
})
export class AppComponent implements OnInit, DoCheck{
  title = 'Gestor de contenidos';
  public identity;
  public token;

  constructor(
    public _userService: UserService
  ) {
    //Buscar los datos de usuario del local storage
    this.loadUser();
  }

  ngOnInit() {
    console.log('Web app cargada correctamente');
  }

  ngDoCheck() {
    //Actualización de las variables identity y token cuando están o no en local storage
    this.loadUser();
  }

  /**
   * Sacar el token y la identidad del usuario del local storage
   * */
  loadUser(){
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }
}
