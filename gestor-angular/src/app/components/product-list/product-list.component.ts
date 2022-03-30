import { Component, OnInit } from '@angular/core';
import {Product} from "../../models/product";
import {ProductService} from "../../services/product.service";
import {global} from "../../services/global";
import {UserService} from "../../services/user.service";

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.css'],
  providers: [ProductService, UserService]
})
export class ProductListComponent implements OnInit {
  public page_title: string;
  public url;
  public products: Array<Product>; //Crear un array de productos para mostrarlos
  public identity;
  public token;

  constructor(
    private _productService: ProductService,
    private _userService: UserService
  ) {
    this.page_title = 'Lista de todos los productos';
    this.url=global.url;
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }

  ngOnInit(): void {
    this.getProducts();
  }

  //Conseguir los productos sacados de los datos de la API REST con subscribe
  getProducts(){
    this._productService.getProducts().subscribe(
      response => {
        if (response.status == 'success'){
          this.products = response.products;
          console.log(this.products);
        }
      },
      error => {
        console.log(error);
      }
    );
  }

}
