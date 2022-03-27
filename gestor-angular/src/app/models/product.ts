export class Product{
  constructor(
    public id: number,
    public nombre_producto: string,
    public descripcion_producto: string,
    public foto: string,
    public category_id: number,
    public tarifa: number,
    public updated_at: any
  ) {}
}
