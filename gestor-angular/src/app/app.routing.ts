import {ModuleWithProviders} from "@angular/core";
import {Routes, RouterModule} from "@angular/router";

//Components
import {LoginComponent} from "./components/login/login.component";
import {RegisterComponent} from "./components/register/register.component";
import {HomeComponent} from "./components/home/home.component";
import {ErrorComponent} from "./components/error/error.component";
import {UserEditComponent} from "./components/user-edit/user-edit.component";
import {CategoryNewComponent} from "./components/category-new/category-new.component";
import {CategoryListComponent} from "./components/category-list/category-list.component";
import {ProductNewComponent} from "./components/product-new/product-new.component";
import {ProductListComponent} from "./components/product-list/product-list.component";
import {ProductDetailComponent} from "./components/product-detail/product-detail.component";
import {ProductEditComponent} from "./components/product-edit/product-edit.component";

//Definir rutas
const appRoutes: Routes = [
  {path: '', component: HomeComponent},
  {path: 'inicio', component: HomeComponent},
  {path: 'login', component: LoginComponent},
  {path: 'register', component: RegisterComponent},
  {path: 'logout/:sure', component: LoginComponent}, //Método de logout si llega el parámetro sure
  {path: 'ajustes', component: UserEditComponent},
  {path: 'crear-categoria', component: CategoryNewComponent},
  {path: 'ver-categorias', component: CategoryListComponent},
  {path: 'crear-producto', component: ProductNewComponent},
  {path: 'ver-productos', component: ProductListComponent},
  {path: 'detalle-producto/:id', component: ProductDetailComponent},
  {path: 'editar-producto/:id', component: ProductEditComponent},
  {path: '**', component: ErrorComponent} //cuando no exista una ruta indicada, saltará error, SIEMPRE AL FINAL
];

//Exportar configuración
export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders<any> = RouterModule.forRoot(appRoutes);
