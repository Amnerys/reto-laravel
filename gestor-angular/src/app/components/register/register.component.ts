import { Component, OnInit } from '@angular/core';
import { FormBuilder } from '@angular/forms';
import {User} from "../../models/user";

@Component({
  selector: 'register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})

export class RegisterComponent implements OnInit {
  public page_title: string;
  public user: User;
  //public formBuilder: FormBuilder;

  constructor() {
    this.page_title = 'Regístrate';
    this.user = new User(1,'', '', '', '', '', '')
  }

  ngOnInit(): void {
    console.log('Componente de registro lanzado');
  }

  onSubmit(form){
    console.log(this.user);
    form.reset();

  }

}
