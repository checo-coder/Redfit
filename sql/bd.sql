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
    `numero` VARCHAR(13) NOT NULL,
    `cedula` VARCHAR(20) NOT NULL,
    `img` VARCHAR(500) NOT NULL,
    `estatus` INT NOT NULL DEFAULT 1
);
ALTER TABLE `medico` 
MODIFY tip_usu INT DEFAULT 2;

SELECT * FROM `medico`;

CREATE table objetivos(
    id_obj INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom_obj VARCHAR(255) NOT NULL,
    desc_obj TEXT NOT NULL,
    estatus INT NOT NULL DEFAULT 1
);
CREATE table clientes(
    id_cli INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom_usr VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    dir_usr VARCHAR(255) NOT NULL,
    edad INT NOT NULL,
    sexo VARCHAR(10) NOT NULL,
    peso DECIMAL(5,2) NOT NULL,
    altura DECIMAL(5,2) NOT NULL,
    img VARCHAR(500) NOT NULL,
    estatus INT NOT NULL DEFAULT 1,
    id_obj INT NOT NULL,
    id_usr int NOT NULL,
    Foreign Key (id_obj) REFERENCES objetivos (id_obj),
    Foreign Key (id_usr) REFERENCES medico (id_usr)

    

);
SELECT * FROM `clientes`;

INSERT INTO `clientes` (`nom_usr`, `mail`, `dir_usr`, `edad`, `sexo`, `peso`, `altura`, `img`, `id_obj`, `id_usr`) VALUES
('sergio', 'sergio.perez@email.com', 'Col. Centro, Casa 4', 28, 'Masculino', 85.50, 1.75, 'https://randomuser.me/api/portraits/men/11.jpg', 1, 13);

SELECT * FROM `medico`;

CREATE table comentarios(
    id_com INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    comentario VARCHAR(255) NOT NULL,
    fecha DATETIME NOT NULL,
    calificacion TINYINT NOT NULL,
    id_cli INT NOT NULL,
    Foreign Key (id_cli) REFERENCES clientes (id_cli)

);

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
SELECT * FROM `plan_semanal`;

DELETE FROM `plan_semanal` WHERE id_plan=2;

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

INSERT INTO `medico` (`nom_usr`, `mail`, `pass`, `dir_usr`, `tip_usu`, `numero`, `cedula`, `img`) VALUES
('Dra. Ana Martínez', 'ana.martinez@hospital.com', 'pass123', 'Av. Reforma 123, CDMX', 1, '5512345678', 'CED1234567', 'https://randomuser.me/api/portraits/women/44.jpg'),
('Dr. Carlos López', 'carlos.lopez@clinica.com', 'securePass', 'Calle 5 de Mayo 45, QRO', 1, '4429876543', 'CED9876543', 'https://randomuser.me/api/portraits/men/32.jpg'),
('Dra. Sofia Ramirez', 'sofia.ramirez@salud.com', 'medico2024', 'Blvd. Bernardo Quintana 90', 1, '4421112233', 'CED5556667', 'https://randomuser.me/api/portraits/women/68.jpg');

INSERT INTO `objetivos` (`nom_obj`, `desc_obj`) VALUES
('Pérdida de Peso', 'Reducción de porcentaje de grasa corporal mediante déficit calórico y ejercicio cardiovascular.'),
('Hipertrofia Muscular', 'Aumento de masa muscular priorizando el entrenamiento de fuerza y superávit calórico.'),
('Mantenimiento', 'Mantener el peso actual mejorando la calidad de la alimentación y hábitos de sueño.'),
('Rehabilitación', 'Recuperación de movilidad y fuerza post-lesión.');

INSERT INTO `clientes` (`nom_usr`, `mail`, `dir_usr`, `edad`, `sexo`, `peso`, `altura`, `img`, `id_obj`, `id_usr`) VALUES
('Juan Pérez', 'juan.perez@email.com', 'Col. Centro, Casa 4', 28, 'Masculino', 85.50, 1.75, 'https://randomuser.me/api/portraits/men/11.jpg', 1, 1),
('María González', 'maria.gonz@email.com', 'Av. Universidad 88', 34, 'Femenino', 62.00, 1.65, 'https://randomuser.me/api/portraits/women/65.jpg', 3, 1),
('Pedro Sánchez', 'pedro.s@email.com', 'Calle Roble 22', 22, 'Masculino', 70.00, 1.80, 'https://randomuser.me/api/portraits/men/22.jpg', 2, 2),
('Luisa Fernández', 'luisa.f@email.com', 'Privada de los Arcos 5', 45, 'Femenino', 68.50, 1.60, 'https://randomuser.me/api/portraits/women/29.jpg', 1, 2),
('Roberto Gomez', 'roberto.g@email.com', 'Calle Industrias 404', 50, 'Masculino', 92.30, 1.78, 'https://randomuser.me/api/portraits/men/54.jpg', 4, 3);

INSERT INTO comentarios (comentario, fecha, calificacion, id_cli) VALUES 
('Excelente atención del nutriólogo, me explicó todo muy bien.', '2023-10-01 10:30:00', 5, 1),
('Las instalaciones están limpias pero hace falta aire acondicionado.', '2023-10-02 14:15:00', 3, 2),
('Muy buen servicio médico, me sentí muy cómoda.', '2023-10-03 09:00:00', 5, 3),
('El sistema de citas falló y tuve que esperar 20 minutos.', '2023-10-05 16:45:00', 2, 1),
('Me encantan los planes de entrenamiento, he visto resultados.', '2023-10-06 11:20:00', 5, 4),
('La doctora fue amable, pero la consulta fue muy rápida.', '2023-10-08 13:10:00', 4, 2),
('No me gustó que me cambiaran la hora de la cita sin avisar.', '2023-10-10 08:30:00', 1, 5),
('Todo excelente, muy profesionales todos en Redfit.', '2023-10-12 18:00:00', 5, 3),
('Buenos precios, pero podrían mejorar la app.', '2023-10-15 20:00:00', 4, 4),
('El objetivo que me asignaron es muy difícil, necesito ajuste.', '2023-10-18 07:45:00', 3, 1);

DELETE FROM `clientes` WHERE id_usr=1;

SELECT * FROM `clientes`;

SELECT * FROM `objetivos`;

SELECT * FROM `medico`;

SELECT * FROM `comentarios`;



-- Fin de archivo bd.sql