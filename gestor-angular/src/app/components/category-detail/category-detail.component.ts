import { Component, OnInit } from '@angular/core';
import {Category} from "../../models/category";
import {Router,ActivatedRoute,Params} from "@angular/router";
import {CategoryService} from "../../services/category.service";

@Component({
  selector: 'app-category-detail',
  templateUrl: './category-detail.component.html',
  styleUrls: ['./category-detail.component.css'],
  providers: [CategoryService]
})
export class CategoryDetailComponent implements OnInit {
  public category: Category;

  constructor(
    private _categoryService: CategoryService,
    private _route: ActivatedRoute,
    private _router: Router
  ) {

  }

  ngOnInit(): void {
    this.getCategoryDetail();
  }

  getCategoryDetail(){
    this._route.params.subscribe(params =>{
      let id = +params ['id']; //params es un array y queremos el id a integer
      console.log(id);

      this._categoryService.getCategoryDetail(id).subscribe(
        response =>{
          if (response.status == 'success'){
            this.category = response.categories;
            console.log(this.category);
          }else{
            this._router.navigate(['error']);
          }
        },
        error => {
          console.log(error);
        }
      );
    });
  }

}
