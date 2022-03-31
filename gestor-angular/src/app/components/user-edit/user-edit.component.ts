import { Component, OnInit } from '@angular/core';
import {User} from "../../models/user";
import {UserService} from "../../services/user.service";
import {global} from "../../services/global";
import {Router} from "@angular/router";

@Component({
  selector: 'app-user-edit',
  templateUrl: './user-edit.component.html',
  styleUrls: ['./user-edit.component.css'],
  providers: [UserService]
})
export class UserEditComponent implements OnInit {

  public page_title;
  public user: User;
  public status : string;
  public identity;
  public token;
  public url;

  //File-uploader para subir imágenes
  public afuConfig = {
    multiple: false,
    formatsAllowed: ".jpg,.png, .gif, .jpeg",
    uploadAPI: {
      url: global.url + 'user/upload',
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
    private _router: Router,
    ) {
    this.page_title = 'Ajustes de usuario';
    this.user = new User(1,'', '', '', '', '', '');
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
    //Rellena los datos de usuario que ya tenemos en identity y son visibles
    this.user = new User(this.identity.sub,this.identity.nombre, this.identity.apellidos, this.identity.fecha_nacimiento,
      this.identity.email, '', this.identity.foto);
    this.url = global.url;
  }

  ngOnInit(): void {
  }

  /**
   * Método que implementa el botón de registro del formulario
   */
  onSubmit(form){
    //Recogemos los datos del formulario de registro y los enviamos al back con el userService
    this._userService.update(this.token, this.user).subscribe(
      response => {
        //Si la respuesta es correcta, cambia el status
        if(response.status = "success"){
          console.log(response);
          this.status = 'success';

          //Si me llega un cambio con algunos de estos campos, actualizarlo
          switch (response['changes']){
            case 'nombre': {
              this.user.nombre = response['changes'].nombre;
              console.log(this.user.nombre);
              break;
            }
            case 'apellidos': {
              this.user.apellidos = response['changes'].apellidos;
              console.log(this.user.apellidos);
              break;
            }
            case 'email': {
              this.user.email = response['changes'].email;
              console.log(this.user.email);
              break;
            }
            case 'foto': {
              this.user.foto = response['changes'].foto;
              console.log(this.user.foto);
              break;
            }
          }
          //Actualizar el usuario en la sesión actual
          this.identity = this.user;
          localStorage.setItem('identity', JSON.stringify(this.identity));

        } else{ //Si es un error, cambio el status a error
          this.status = 'error';
        }
      },
      error => {
        console.log(<any>error);
      }
    );
  }

  //Método para guardar la imagen en el usuario
  avatarUpload(datos){
    this.user.foto = datos.body.image;
  }

  //Método para borrar al usuario, nos redirigirá al inicio
  deleteUser(id){
    this._userService.delete(this.token, id).subscribe(
      response => {
        //Redirección a la página de inicio
        this._router.navigate(['inicio']);
      },
      error => {
        console.log(error);
      }
    );
  }

}
