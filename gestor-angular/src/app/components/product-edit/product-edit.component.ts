import { Component, OnInit } from '@angular/core';
import {Product} from "../../models/product";
import {global} from "../../services/global";
import {UserService} from "../../services/user.service";
import {CategoryService} from "../../services/category.service";
import {ProductService} from "../../services/product.service";
import {ActivatedRoute, Router} from "@angular/router";

@Component({
  selector: 'app-product-edit',
  templateUrl: './product-edit.component.html',
  styleUrls: ['./product-edit.component.css'],
  providers: [ProductService, CategoryService, UserService]
})
export class ProductEditComponent implements OnInit {

  public page_title: string;
  public product: Product;
  public status : string;
  public identity;
  public token;
  public url;
  public categories;
  private categoryName;

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
    this.page_title = 'Edita el producto';
    this.url = global.url;
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }

  ngOnInit(): void {
    this.getCategories();
    this.getProductDetail();
    this.product = new Product(1,'', '', '', 1, 0, '' );
    console.log(this.product);
  }

  onSubmit(form){
    this._productService.update(this.token,this.product, this.product.id).subscribe(
      response => {
        if(response.status == 'success'){
          this.product = response.product;
          this.status = 'success';
          this._router.navigate(['/detalle-producto', this.product.id])//redirigir a la vista de detalle del producto
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

  getProductDetail(){
    this._route.params.subscribe(params =>{
      let id = +params['id']; //sacamos el id de params como un int
      //Le pasamos el id para sacar los detalles del producto que corresponde a ese id
      this._productService.getProductDetail(id).subscribe(
        response => {
          if (response.status == 'success'){
            this.product = response.product;
            console.log(this.product);
            this.categoryName = response.product.categorias[0].nombre_categoria
            console.log(this.categoryName);
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
