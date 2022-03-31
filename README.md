# Reto-laravel
Reto en Laravel para Studiogenesis hecho por Mónica Ferrer (Amnerys) &copy;.

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
Migrar los datos dummies para la Base de Datos con el comando:

`php artisan migrate:fresh --seed`

Hay datos para crear la base de datos en el documento `database.sql`.
Crear la base de datos con:

`create database if not exists reto_laravel;`

Usar la base de datos:

`use reto_laravel;`

Levantar servidor para ejecutar proyecto de Angular:

`ng serve`

Usar la dirección proporcionada en el navegador para ejecutar el proyecto:

`http://localhost:4200/`
