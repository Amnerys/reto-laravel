export class User{
  constructor(
    public id: number,
    public nombre: string,
    public apellidos: string,
    public fecha_nacimiento: string,
    public email: string,
    public password: string,
    public foto: string
  ) {}
}
