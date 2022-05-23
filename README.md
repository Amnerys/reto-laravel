# Reto-laravel
Reto en Laravel hecho por Mónica Ferrer (Amnerys) &copy;.

## Detalles:
El BackEnd está formado con Laravel 9 y PHP 8 y basado en API RESTful, con 3 CRUDs:
1) **Usuarios**: crear, editar y eliminar
2) **Categorías**: crear, editar y eliminar
3) **Productos**: crear, editar y eliminar

Está compuesto por las tres clases modelo, sus seeders, factories y migrations.
Productos y Categorías están relacionados.

El FrontEnd está creado en Angular 12 y TypeScript 4.6.3, HTML5 con Bootstrap 4, Froala y AngularFileUploader.
Los tres CRUD tienen sus componentes, con sus servicios y clases modelo. 

**Productos** y **categorías** tienen vistas de:

- Listado de todas las que incluye la base de datos
- Crear un nuevo ítem
- Vista de detalle del item seleccionado
- Edición del item
- Borrado del item

**Usuarios** tiene vistas de:

- Registro de usuarios (crear)
- Login de usuarios (validado por la base de datos, con password encriptada)
- Ajustes de usuarios (edición y borrado)

Además incluye vista para la página de inicio y para la página de error.

## Instrucciones:
1) Entra dentro de la ruta del proyecto de laravel `crud-api-rest` y ejecuta:

`composer update`

2) Cambiar los datos del fichero `.env` dentro de la carpeta crud-api-rest por:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reto_laravel
DB_USERNAME=root
DB_PASSWORD=
```
He añadido un fichero `.env` dentro de un .zip para facilitarlo.

3) Crear la base de datos en PhpMyAdmin con la SQL:

`create database if not exists reto_laravel;`

4) Usar la base de datos en PhpMyAdmin con la SQL:

`use reto_laravel;`

5) Migrar los datos dummies para la Base de Datos con el comando:

`php artisan migrate:fresh --seed`

Hay datos para crear la base de datos en el documento `database.sql`. 
Pero **solo necesita crear la base de datos**, las migraciones se encargan de aportar datos.

6) Instalar angular dentro de la ruta del proyecto de angular `gestor-angular`:

`npm install - g @angular/cli`

7) Levantar servidor para ejecutar proyecto de Angular dentro de la ruta del proyecto de angular:

`npm run ng serve`

8) Usar la dirección proporcionada en el navegador para ejecutar el proyecto:

`http://localhost:4200/`

9) **OPCIONAL**: Si te da problemas de CORS, usa un navegador con la seguridad CORS desactivada. En ejecutar:

`chrome.exe --user-data-dir="C://Chrome dev session" --disable-web-security`
