-- Active: 1752517586065@@127.0.0.1@3306@redfit
-- Crear BD si no existe y seleccionarla
CREATE DATABASE  Redfit;
USE Redfit;

-- Tablas
CREATE TABLE `medico`(
    `id_usr` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom_usr` VARCHAR(255) NOT NULL,
    `mail` VARCHAR(255) NOT NULL,
    `pass` VARCHAR(255) NOT NULL,
    `dir_usr` VARCHAR(255) NOT NULL,
    `tip_usu` INT NOT NULL,
    `rfc` VARCHAR(13) NOT NULL,
    `cedula` VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS `comentarios`(
    `id_com` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `comentario` VARCHAR(255) NOT NULL,
    `fecha` DATETIME NOT NULL,
    `estatus` INT NOT NULL,
    `usr_id` INT NOT NULL,
    `blog_id` INT NOT NULL
);

CREATE TABLE IF NOT EXISTS `blog`(
    `id_blog` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `titulo` VARCHAR(255) NOT NULL,
    `desc` TEXT NOT NULL,
    `img` VARCHAR(255) NOT NULL,
    `tags` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `catalogo`(
    `id_prod` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom_prod` VARCHAR(255) NOT NULL,
    `desc` VARCHAR(500) NOT NULL,
    `prec` DECIMAL(8, 2) NOT NULL,
    `img` VARCHAR(500) NOT NULL,
    `estatus` INT NOT NULL,
    `stock` INT NOT NULL
);

-- CREATE TABLE IF NOT EXISTS `compra`(
--     `id` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--     `cliente_id` INT NOT NULL,
--     `prod_id` INT NOT NULL,
--     `fecha` DATETIME NOT NULL,
--     `importe` INT NOT NULL
-- );


-- DESDE AQUÍ CAMBIA LA BASE DE DATOS 
-- DESDE AQUÍ CAMBIA LA BASE DE DATOS -- DESDE AQUÍ CAMBIA LA BASE DE DATOS 
-- DESDE AQUÍ CAMBIA LA BASE DE DATOS 

-- Tabla para guardar las recetas creadas por los médicos
CREATE TABLE recetas (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    ingredientes TEXT NOT NULL,
    descripcion TEXT,
    preparacion TEXT NOT NULL,
    calorias INT NOT NULL,
    id_medico INT NOT NULL, -- Quién creó la receta
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para asignar recetas a clientes (Plan Semanal)
CREATE TABLE plan_semanal (
    id_plan INT AUTO_INCREMENT PRIMARY KEY,
    id_medico INT NOT NULL,
    id_cliente INT NOT NULL,
    id_receta INT NOT NULL,
    dia_semana ENUM('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo') NOT NULL,
    tipo_comida ENUM('Desayuno', 'Almuerzo', 'Comida', 'Cena') NOT NULL,
    UNIQUE KEY unique_plan (id_cliente, dia_semana, tipo_comida) -- Evita duplicar comidas el mismo día/hora
);

-- Tabla p ara Citas
CREATE TABLE citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_medico INT NOT NULL,
    id_cliente INT NOT NULL,
    fecha_cita DATE NOT NULL,
    hora_cita TIME NOT NULL,
    motivo VARCHAR(255),
    estatus ENUM('Pendiente', 'Confirmada', 'Cancelada') DEFAULT 'Pendiente'
);

-- DESDE AQUÍ TERMINAN LOS CAMBIOS 
-- DESDE AQUÍ TERMINAN LOS CAMBIOS -- DESDE AQUÍ TERMINAN LOS CAMBIOS
-- DESDE AQUÍ TERMINAN LOS CAMBIOS 

-- Datos de ejemplo
INSERT INTO medico
VALUES
(1, 'Sergio Altamira Mojarro', 'sergio@gmail.com', 'sergio01.', 'Av. Pie de la Cuesta 2501, Nacional, 76148 Santiago de Querétaro, Qro.', 1, 'AAMS060121', '12345678912'),  -- rol 1 = admin
(2,'Asael Aljeandro',      'asa@gmail.com',      'abcd', 'Av. Pie de la Cuesta 2501, Nacional, 76148 Santiago de Querétaro, Qro.', 2, 'CEVA0606IT3', 'LJRA6112');  -- rol 2 = medico

-- 4 INSERTS de Cursos
INSERT INTO catalogo (nom_prod, `desc`, prec, img, estatus, stock)
VALUES ('Curso de HTML5', 'Aprende los fundamentos del lenguaje de marcado más utilizado en la web. Este curso te enseña a estructurar páginas web modernas con HTML5, incluyendo etiquetas semánticas, formularios avanzados y elementos multimedia. Ideal para principiantes que desean construir sitios desde cero.', 499.00, 'https://www.oxfordwebstudio.com/user/pages/06.da-li-znate/sta-je-html/sta-je-html.jpg', 1, 25);

INSERT INTO catalogo (nom_prod, `desc`, prec, img, estatus, stock)
VALUES ('Curso de CSS3', 'Domina el arte del diseño web con CSS3. Aprende a estilizar tus páginas con selectores, flexbox, grid, animaciones y transiciones. Este curso te permitirá crear interfaces visualmente atractivas y responsivas para cualquier dispositivo.', 549.00, 'https://www.oxfordwebstudio.com/user/pages/06.da-li-znate/sta-je-css/sta-je-css.png', 1, 18);

INSERT INTO catalogo (nom_prod, `desc`, prec, img, estatus, stock)
VALUES ('Curso de JavaScript', 'Explora el lenguaje que da vida a la web. Este curso de JavaScript te lleva desde los conceptos básicos hasta la manipulación del DOM, eventos, funciones, objetos y programación asincrónica. Aprende a crear interactividad y lógica en tus proyectos web.', 599.00, 'https://soyhorizonte.com/wp-content/uploads/2020/10/Javascript-by-SoyHorizonte.jpg', 1, 30);

INSERT INTO catalogo (nom_prod, `desc`, prec, img, estatus, stock)
VALUES ('Curso de Django con Python', 'Construye aplicaciones web robustas con Django, el framework de alto nivel basado en Python. Aprende a crear modelos, vistas, formularios, autenticación y paneles administrativos. Este curso es perfecto para quienes desean desarrollar sitios dinámicos y seguros con rapidez.', 399.00, 'https://impulso06.com/wp-content/uploads/2023/11/Python-y-Django-Herramientas-esenciales-para-el-desarrollo-web-moderno.png', 1, 12);
