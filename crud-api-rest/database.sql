create database if not exists reto_laravel;
use reto_laravel;

create table users(
id      int(255) auto_increment not null,
nombre    varchar (50) not null,
apellidos    varchar (100),
fecha_nacimiento    date,
email    varchar (255) not null,
password    varchar (255) not null,
foto    varchar (255),
created_at  datetime default null,
updated_at    datetime default null,
remember_token    varchar (255),
CONSTRAINT pk_usuarios PRIMARY KEY(id)
)ENGINE=InnoDb;

create table categories(
id      int(255) auto_increment not null,
nombre_categoria    varchar (100) not null,
descripcion_categoria    text,
created_at  datetime default null,
updated_at    datetime default null,
CONSTRAINT pk_categorias PRIMARY KEY(cod_categoria)
)ENGINE=InnoDb;

create table products(
id      int(255) auto_increment not null,
nombre_producto    varchar (100) not null,
descripcion_producto    text,
foto    varchar (255),
categoria_id    int(255) not null,
tarifa    decimal (10,2),
created_at  datetime default null,
updated_at    datetime default null,
CONSTRAINT pk_productos PRIMARY KEY(cod_producto),
CONSTRAINT fk_producto_categoria FOREIGN KEY(categoria_id) REFERENCES categorias(cod_categoria)
)ENGINE=InnoDb;

INSERT INTO usuarios VALUES (NULL,'admin','administrador','2022-05-25','admin@admin.com','admin',NULL,
CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

INSERT INTO categorias VALUES (NULL, 'Smartphones', 'Todos los Smartphones disponibles, tanto Android como IOS.',
CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO categorias VALUES (NULL, 'Libretas', 'Libretas de todos los tamaños y colores.',
CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO productos VALUES (NULL, 'Xiaomi Redmi Note 12',
'Sistema operativo:	Android\r\nProcesador:	Qualcomm Snapdragon 8 Gen 1\r\nVelocidad Procesador:	3.0 GHz\r\nCapacidad memoria:	256 GB',
NULL, '1', '1099.99', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO productos VALUES (NULL, 'Apple iPhone 12, Purple', 'Sistema operativo: iOS\r\nProcesador: Chip A14 Bionic con Neural Engine\r\nCapacidad memoria: 128 GB',
NULL, '1', '758.00', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO productos VALUES (NULL, 'Cuaderno Oxford A4, 120 hojas', 'Tapa extradura, máxima resistencia y protección de las hojas, y con un tacto suave.\r\nRecuadro de color a juego con la tapa flamingo pastel.',
NULL, '2', '4.75', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
