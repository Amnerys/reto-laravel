import { Component, OnInit, DoCheck } from '@angular/core';
import { UserService } from "./services/user.service";
import {global} from "./services/global";
import {CategoryService} from "./services/category.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [UserService, CategoryService]
})
export class AppComponent implements OnInit, DoCheck{
  title = 'Gestor de contenidos';
  public identity;
  public token;
  public url;
  public categories;

  constructor(
    private _userService: UserService,
    private _categoryService: CategoryService
  ) {
    //Buscar los datos de usuario del local storage
    this.loadUser();
    this.url = global.url;
  }

  ngOnInit() {
    console.log('Web app cargada correctamente');
    this.getCategories();
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

  getCategories(){
    this._categoryService.getCategories().subscribe(
      response=>{
        if (response.status == 'success'){
          this.categories = response.categories;
          console.log(this.categories);
        }
      },
      error => {
        console.log(error);
      }
    );
  }
}
