import { Component, OnInit } from '@angular/core';
import {Product} from "../../models/product";
import {global} from "../../services/global";
import {UserService} from "../../services/user.service";
import {Router, ActivatedRoute, Params} from "@angular/router";
import {CategoryService} from "../../services/category.service";
import {ProductService} from "../../services/product.service";

@Component({
  selector: 'app-product-new',
  templateUrl: './product-new.component.html',
  styleUrls: ['./product-new.component.css'],
  providers: [UserService, CategoryService, ProductService]
})
export class ProductNewComponent implements OnInit {

  public page_title: string;
  public product: Product;
  public status : string;
  public identity;
  public token;
  public url;
  public categories;

  //File-uploader para subir imágenes
  public afuConfig = {
    multiple: false,
    formatsAllowed: ".jpg,.png, .gif, .jpeg",
    uploadAPI: {
      url: global.url + 'product/upload',
      headers: {
        "Authorization" : this._userService.getToken() //Que autorice al usuario con token
      },
    },
    hideProgressBar: false,
    hideResetBtn: true,
    hideSelectBtn: false,
  };

  constructor(
    private _userService: UserService,
    private _categoryService: CategoryService,
    private _productService: ProductService,
    private _route: ActivatedRoute,
    private _router: Router
  ) {
    this.page_title = 'Añade un nuevo producto';
    this.url = global.url;
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }

  ngOnInit(): void {
    this.getCategories();
    this.product = new Product(1,'', '', '', 1, 0, '' );
    console.log(this.product);
  }

  onSubmit(form){
    console.log(this.product);
    console.log(this._productService.pruebas());
    this._productService.create(this.token,this.product).subscribe(
      response => {
        if(response.status == 'success'){
          this.product = response.product;
          this.status = 'success';
        }else{
          console.log('error response');
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

  imageUpload(datos){
    this.product.foto = datos.body.image;
  }

  //Recogemos las categorías desde el Servicio de categorías recogiendo los resultados a la llamada del servicio con subscribe
  getCategories(){
    this._categoryService.getCategories().subscribe(
      response =>{
        if(response.status == 'success'){
          this.categories = response.categories; //nos recogerá las categorías desde el backend y rellenamos el objeto categories
          console.log(this.categories); //compruebo que lleguen categorias
        }
      },
      error => {
        console.log(error)
      }
    );
  }

}
