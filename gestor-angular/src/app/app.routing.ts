import {ModuleWithProviders} from "@angular/core";
import {Routes, RouterModule} from "@angular/router";

//Components
import {LoginComponent} from "./components/login/login.component";
import {RegisterComponent} from "./components/register/register.component";
import {HomeComponent} from "./components/home/home.component";
import {ErrorComponent} from "./components/error/error.component";
import {UserEditComponent} from "./components/user-edit/user-edit.component";
import {CategoryNewComponent} from "./components/category-new/category-new.component";

//Definir rutas
const appRoutes: Routes = [
  {path: '', component: HomeComponent},
  {path: 'inicio', component: HomeComponent},
  {path: 'login', component: LoginComponent},
  {path: 'register', component: RegisterComponent},
  {path: 'logout/:sure', component: LoginComponent}, //Método de logout si llega el parámetro sure
  {path: 'ajustes', component: UserEditComponent},
  {path: 'crear-categoria', component: CategoryNewComponent},
  {path: '**', component: ErrorComponent} //cuando no exista una ruta indicada, saltará error, SIEMPRE AL FINAL
];

//Exportar configuración
export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders<any> = RouterModule.forRoot(appRoutes);
