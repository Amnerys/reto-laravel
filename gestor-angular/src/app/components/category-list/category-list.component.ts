import { Component, OnInit } from '@angular/core';
import {Category} from "../../models/category";
import {CategoryService} from "../../services/category.service";
import {UserService} from "../../services/user.service";

@Component({
  selector: 'app-category-list',
  templateUrl: './category-list.component.html',
  styleUrls: ['./category-list.component.css'],
  providers: [CategoryService,UserService]
})
export class CategoryListComponent implements OnInit {

  public page_title;
  public category: Category;
  public status : string;
  public url;
  public identity;
  public token;
  public categories: Array<Category>; //Crear un array de productos para mostrarlos

  constructor(
    private _categoryService: CategoryService,
    private _userService: UserService
  ) {
    this.page_title = "Consulta todas las categorías";
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }

  ngOnInit(): void {
    this.getCategories()
  }

  //Conseguir los productos sacados de los datos de la API REST con subscribe
  getCategories(){
    this._categoryService.getCategories().subscribe(
      response => {
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

  //Borrar una categoría por su id
  deleteCategory(id){
    this._categoryService.delete(this.token,id).subscribe(
      response => {
        this.getCategories();
      },
      error => {
        console.log(error);
      }
    );
  }

}
