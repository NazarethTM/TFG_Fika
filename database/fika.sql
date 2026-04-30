-- =====================================================
-- Fika - Base de datos de la cafetería de estudio
-- TFG DAW 2025/2026 - Nazareth, Ken, Laura
-- =====================================================
-- Cambios respecto a la versión inicial:
--   - Arreglados los `;` faltantes en cursos e inscripciones
--   - Eliminado stripe_session_id (no usamos pasarela real)
--   - usuarios: añadidos apellidos, telefono, activo, updated_at
--   - mesas: eliminada `zona`, todas las mesas son reservables por igual
--   - mesas: añadidas pos_x / pos_y para el mapa interactivo + activa
--   - reservas: n_personas, notas, CHECK de horario, índice compuesto
--   - cursos: tipo, duracion_min INT, fecha_inicio DATETIME, naming consistente
--   - inscripciones: reescrita con el mismo estilo que el resto
--   - pedidos: pagado, metodo_pago, fecha_recogida, notas
--   - detalles_pedido: precio_unitario para congelar el precio histórico
--   - Nueva tabla chat_mensajes para el bot de Ken
--   - Naming en inglés para campos de sistema (created_at, updated_at)
-- =====================================================

DROP DATABASE IF EXISTS fika_tfg;
CREATE DATABASE fika_tfg CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fika_tfg;


-- -----------------------------------------------------
-- USUARIOS
-- -----------------------------------------------------
CREATE TABLE usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(80)  NOT NULL,
    apellidos   VARCHAR(120) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    telefono    VARCHAR(20)  NULL,
    password    VARCHAR(255) NOT NULL,                       -- siempre password_hash() de PHP, nunca texto plano
    rol         ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
    activo      TINYINT(1)   NOT NULL DEFAULT 1,             -- baja lógica sin perder histórico
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME     NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);


-- -----------------------------------------------------
-- MESAS (con coordenadas para el mapa interactivo)
-- pos_x y pos_y se guardan como % sobre la imagen del local
-- así el mapa es responsive sin recalcular nada en JS
-- -----------------------------------------------------
CREATE TABLE mesas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    codigo      VARCHAR(10) NOT NULL UNIQUE,                 -- M01, M02...
    capacidad   INT         NOT NULL DEFAULT 2,
    pos_x       INT         NOT NULL,                        -- 0..100, % horizontal sobre la imagen del local
    pos_y       INT         NOT NULL,                        -- 0..100, % vertical sobre la imagen del local
    activa      TINYINT(1)  NOT NULL DEFAULT 1               -- el admin puede dar de baja una mesa sin borrarla
);


-- -----------------------------------------------------
-- RESERVAS DE MESA
--
-- IMPORTANTE: el solapamiento de horarios se valida en PHP
-- antes de hacer INSERT. MySQL no puede expresar
-- "no permitir que dos reservas en la misma mesa se pisen"
-- como restricción de tabla. La consulta de validación es:
--
--   SELECT id FROM reservas
--   WHERE mesa_id = ? AND fecha = ? AND estado = 'activa'
--     AND hora_inicio < ? AND hora_fin > ?;
--
-- Si devuelve filas, no se puede reservar.
-- -----------------------------------------------------
CREATE TABLE reservas (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id   INT          NOT NULL,
    mesa_id      INT          NOT NULL,
    fecha        DATE         NOT NULL,
    hora_inicio  TIME         NOT NULL,
    hora_fin     TIME         NOT NULL,
    n_personas   INT          NOT NULL DEFAULT 1,
    notas        VARCHAR(255) NULL,
    estado       ENUM('activa','cancelada','finalizada','no_asistio') NOT NULL DEFAULT 'activa',
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (mesa_id)    REFERENCES mesas(id)    ON DELETE CASCADE,
    CONSTRAINT chk_reserva_horario CHECK (hora_fin > hora_inicio)
);

CREATE INDEX idx_reservas_mesa_fecha ON reservas(mesa_id, fecha, estado);


-- -----------------------------------------------------
-- CURSOS (clases de repostería, barista, catas, etc.)
-- -----------------------------------------------------
CREATE TABLE cursos (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(150) NOT NULL,
    descripcion   TEXT         NULL,
    tipo          ENUM('reposteria','barista','cata','otro') NOT NULL DEFAULT 'otro',
    instructor    VARCHAR(120) NOT NULL,
    nivel         ENUM('Principiante','Intermedio','Avanzado') NOT NULL DEFAULT 'Principiante',
    precio        DECIMAL(8,2) NOT NULL,
    duracion_min  INT          NULL,                         -- duración total en minutos
    fecha_inicio  DATETIME     NOT NULL,                     -- día y hora del curso
    fecha_fin     DATETIME     NULL,
    cupo_maximo   INT          NOT NULL DEFAULT 10,
    imagen        VARCHAR(255) NULL,
    activo        TINYINT(1)   NOT NULL DEFAULT 1,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_cursos_fecha ON cursos(fecha_inicio, activo);


-- -----------------------------------------------------
-- INSCRIPCIONES a cursos
-- (mismo estilo que el resto del esquema)
-- -----------------------------------------------------
CREATE TABLE inscripciones (
    id                INT          AUTO_INCREMENT PRIMARY KEY,
    usuario_id        INT          NOT NULL,
    curso_id          INT          NOT NULL,
    estado            ENUM('pendiente','confirmada','cancelada','completada') NOT NULL DEFAULT 'pendiente',
    pagado            TINYINT(1)   NOT NULL DEFAULT 0,
    importe_pagado    DECIMAL(8,2) NULL,
    pagado_en         DATETIME     NULL,
    fecha_inscripcion DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id)   REFERENCES cursos(id)   ON DELETE CASCADE,
    UNIQUE KEY uq_usuario_curso (usuario_id, curso_id)        -- impide doble inscripción al mismo curso
);


-- -----------------------------------------------------
-- PRODUCTOS (catálogo de café y repostería)
-- -----------------------------------------------------
CREATE TABLE productos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    descripcion TEXT         NULL,
    categoria   ENUM('cafe','reposteria','bebida','otro') NOT NULL DEFAULT 'otro',
    precio      DECIMAL(6,2) NOT NULL,
    imagen      VARCHAR(255) NULL,
    disponible  TINYINT(1)   NOT NULL DEFAULT 1,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- -----------------------------------------------------
-- PEDIDOS (cabecera)
-- -----------------------------------------------------
CREATE TABLE pedidos (
    id              INT          AUTO_INCREMENT PRIMARY KEY,
    usuario_id      INT          NOT NULL,
    total           DECIMAL(8,2) NOT NULL,
    estado          ENUM('pendiente','preparando','listo','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
    pagado          TINYINT(1)   NOT NULL DEFAULT 0,
    metodo_pago     ENUM('simulado','tarjeta','efectivo') NOT NULL DEFAULT 'simulado',
    fecha_recogida  DATETIME     NULL,                       -- cuándo viene el cliente a recoger
    notas           VARCHAR(255) NULL,                       -- alergias, modificaciones, etc.
    created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE INDEX idx_pedidos_estado ON pedidos(estado);


-- -----------------------------------------------------
-- DETALLES DE PEDIDO
-- precio_unitario congela el precio del producto en el momento del pedido,
-- así si mañana sube el café, los pedidos viejos siguen siendo correctos.
-- -----------------------------------------------------
CREATE TABLE detalles_pedido (
    id               INT          AUTO_INCREMENT PRIMARY KEY,
    pedido_id        INT          NOT NULL,
    producto_id      INT          NOT NULL,
    cantidad         INT          NOT NULL DEFAULT 1,
    precio_unitario  DECIMAL(6,2) NOT NULL,
    subtotal         DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (pedido_id)   REFERENCES pedidos(id)   ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
);


-- -----------------------------------------------------
-- CHATBOT - log de conversaciones (módulo de Ken)
-- Útil para:
--   * Depurar el prompt durante desarrollo
--   * Mostrar historial reciente al admin (queda muy bien en defensa)
--   * Mantener el contexto si el usuario refresca la página
-- -----------------------------------------------------
CREATE TABLE chat_mensajes (
    id          INT          AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT          NULL,                           -- NULL si no estaba logueado
    sesion      VARCHAR(64)  NOT NULL,                       -- session_id() de PHP, agrupa la conversación
    rol         ENUM('user','assistant') NOT NULL,
    mensaje     TEXT         NOT NULL,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE INDEX idx_chat_sesion ON chat_mensajes(sesion, created_at);


-- =====================================================
-- DATOS DE PRUEBA
-- =====================================================

-- Mesas distribuidas por el local (pos_x / pos_y son % sobre la imagen del fondo)
/*INSERT INTO mesas (codigo, capacidad, pos_x, pos_y) VALUES
('M01', 2, 15, 20),
('M02', 2, 15, 45),
('M03', 4, 35, 20),
('M04', 4, 60, 25),
('M05', 6, 80, 30),
('M06', 2, 25, 75),
('M07', 4, 55, 75),
('M08', 4, 80, 70);


-- Productos de ejemplo
INSERT INTO productos (nombre, descripcion, categoria, precio) VALUES
('Espresso',            'Café espresso de tueste medio',         'cafe',       1.50),
('Cappuccino',          'Espresso con leche y espuma',           'cafe',       2.20),
('Café con leche',      'Clásico café con leche',                'cafe',       1.80),
('Croissant',           'Croissant artesano de mantequilla',     'reposteria', 1.90),
('Tarta de zanahoria',  'Porción de tarta casera',               'reposteria', 3.50),
('Cookie de chocolate', 'Cookie XL con chips de chocolate',      'reposteria', 2.00),
('Limonada casera',     'Limonada con menta fresca',             'bebida',     2.80),
('Té matcha',           'Matcha latte preparado al momento',     'bebida',     3.20);


-- Cursos de ejemplo
INSERT INTO cursos (titulo, descripcion, tipo, instructor, nivel, precio, duracion_min, fecha_inicio, cupo_maximo) VALUES
('Iniciación al barista',   'Aprende espresso, latte art y métodos filtrados',     'barista',    'Nora Vega',   'Principiante', 35.00, 120, '2026-05-15 18:00:00',  8),
('Cupcakes de primavera',   'Decoración de cupcakes con buttercream',              'reposteria', 'Iván Castro', 'Principiante', 28.00, 150, '2026-05-22 17:30:00', 10),
('Cata de cafés de origen', 'Diferencias entre orígenes africanos y americanos',   'cata',       'Nora Vega',   'Intermedio',   25.00,  90, '2026-05-29 19:00:00', 12);

*/
-- =====================================================
-- USUARIO ADMINISTRADOR
-- =====================================================
-- No insertamos al admin desde SQL para no guardar la contraseña en texto plano.
-- El proceso correcto es:
--   1. Importar este archivo en phpMyAdmin
--   2. Registrarse desde el formulario de la web (la contraseña se hashea con password_hash)
--   3. Promocionar a ese usuario a admin con la siguiente sentencia:
--
--      UPDATE usuarios SET rol = 'admin' WHERE email = 'tu_email@fika.com';
--
-- Así la contraseña queda correctamente hasheada y password_verify() funcionará.
-- =====================================================
