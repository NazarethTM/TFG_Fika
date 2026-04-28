-- CREACIÓN DE BASE DE DATOS
CREATE DATABASE IF NOT EXISTS fika_db;
USE fika_db;

-- USUARIOS
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente','admin') DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- MESAS
CREATE TABLE mesas (
    id_mesa INT AUTO_INCREMENT PRIMARY KEY,
    nombre_mesa VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL
);

-- RESERVAS DE MESAS
CREATE TABLE reservas_mesas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_mesa INT NOT NULL,
    
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    
    duracion_min INT NOT NULL,
    estado ENUM('activa','cancelada') DEFAULT 'activa',
    
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP NULL,
    
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_mesa) REFERENCES mesas(id_mesa)
);

-- CLASES DE REPOSTERÍA
CREATE TABLE clases (
    id_clase INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    plazas_maximas INT NOT NULL
);

-- RESERVAS DE CLASES
CREATE TABLE reservas_clases (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_clase INT NOT NULL,
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,

    FOREIGN KEY (id_clase) REFERENCES clases(id_clase)
        ON DELETE CASCADE
);

-- PRODUCTOS CAFETERÍA
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(6,2) NOT NULL,
    stock INT DEFAULT 0,
    categoria VARCHAR(50)
);

-- PEDIDOS
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente','preparado','recogido') DEFAULT 'pendiente',
    total DECIMAL(8,2) DEFAULT 0,

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- DETALLE DE PEDIDO
CREATE TABLE detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(6,2) NOT NULL,

    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
        ON DELETE CASCADE,

    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
        ON DELETE CASCADE
);

-- CHATBOT (OPCIONAL)
CREATE TABLE mensajes_chat (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    mensaje TEXT NOT NULL,
    respuesta TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE SET NULL
);