import { Component, OnInit } from '@angular/core';
import {global} from "../../services/global";
import {UserService} from "../../services/user.service";
import {CategoryService} from "../../services/category.service";
import {ActivatedRoute, Router} from "@angular/router";
import {Category} from "../../models/category";

@Component({
  selector: 'app-category-edit',
  templateUrl: './category-edit.component.html',
  styleUrls: ['./category-edit.component.css'],
  providers: [CategoryService,UserService]
})
export class CategoryEditComponent implements OnInit {
  public page_title: string;
  public category: Category;
  public status;
  public identity;
  public token;
  public url;

  constructor(
    private _userService: UserService,
    private _categoryService: CategoryService,
    private _route: ActivatedRoute,
    private _router: Router
  ) {
    this.page_title = 'Edita la categoría';
    this.url = global.url;
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
    this.category = new Category(1,'', '');
  }

  ngOnInit(): void {
    this.getCategoryDetail();
    console.log(this.category);
    this.category = new Category(this.category.id,this.category.nombre_categoria, this.category.descripcion_categoria);
  }

  onSubmit(form){
    this._categoryService.update(this.token,this.category, this.category.id).subscribe(
      response => {
        if(response.status == 'success'){
          this.category = response.category;
          this.status = 'success';
          this._router.navigate(['/ver-categorias'])//redirigir a la vista de las categorias
        }else{
          this.status = 'error';
        }
      },
      error => {
        console.log('error fuera de response');
        console.log(error);
        this.status = 'error';
      }
    );
  }

  getCategoryDetail(){
    this._route.params.subscribe(params =>{
      let id = +params['id']; //sacamos el id de params como un int
      //Le pasamos el id para sacar los detalles del producto que corresponde a ese id
      this._categoryService.getCategoryDetail(id).subscribe(
        response => {
          if (response.status == 'success'){
            this.category = response.category;
            console.log(this.category);
          }else {
            console.log('Ha habido un error');
          }
        },
        error => {
          console.log(error);
          this._router.navigate(['/error']);
        }
      );
    });
  }

}
