import { Component, OnInit } from '@angular/core';
import {ProductService} from "../../services/product.service";
import {Product} from "../../models/product";
import {Router, ActivatedRoute, Params} from "@angular/router";
import {CategoryService} from "../../services/category.service";
import {global} from "../../services/global";

@Component({
  selector: 'app-product-detail',
  templateUrl: './product-detail.component.html',
  styleUrls: ['./product-detail.component.css'],
  providers: [ProductService,CategoryService]
})
export class ProductDetailComponent implements OnInit {
  public product: Product;
  public page_title: string;
  public categoryName;
  public url;

  constructor(
    private _productService: ProductService,
    private _categoryService: CategoryService,
    private _route: ActivatedRoute,
    private _router: Router
  ) {
    this.page_title = 'Detalles del producto';
    this.url = global.url;
  }

  ngOnInit(): void {
    this.getProductDetail();
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
