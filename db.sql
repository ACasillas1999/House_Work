CREATE DATABASE clinica;

USE clinica;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  correo VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  rol ENUM('admin', 'doctor', 'recepcion') DEFAULT 'recepcion'
);

CREATE TABLE pacientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  fecha_nacimiento DATE,
  telefono VARCHAR(20),
  correo VARCHAR(100),
  direccion TEXT,
  genero ENUM('Masculino', 'Femenino', 'Otro')
);

CREATE TABLE citas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_paciente INT,
  id_usuario INT,
  fecha DATE,
  hora TIME,
  motivo TEXT,
  estado ENUM('Programada', 'Cancelada', 'Realizada') DEFAULT 'Programada',
  FOREIGN KEY (id_paciente) REFERENCES pacientes(id),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE historiales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_paciente INT,
  fecha DATETIME,
  descripcion TEXT,
  receta TEXT,
  archivo TEXT,
  FOREIGN KEY (id_paciente) REFERENCES pacientes(id)
);


ALTER TABLE pacientes
ADD tipo_sangre VARCHAR(10),
ADD alergias TEXT,
ADD enfermedades TEXT,
ADD antecedentes TEXT;
