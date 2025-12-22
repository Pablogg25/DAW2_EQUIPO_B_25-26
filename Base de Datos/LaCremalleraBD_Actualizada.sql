-- Creación de la base de datos
CREATE DATABASE la_cremallera CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'admin'@'%' IDENTIFIED BY '123456789';
GRANT ALL PRIVILEGES ON la_cremallera.* TO 'admin'@'%';
FLUSH PRIVILEGES;

SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES';
USE la_cremallera;

-- Tabla usuarios
CREATE TABLE usuarios (
    usuarioId INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100) UNIQUE NOT NULL,
    direccion VARCHAR(150),
    username VARCHAR(50) UNIQUE NOT NULL,
    password_SHA2 VARCHAR(255) NOT NULL,
    rol ENUM('admin','empleado','cliente') NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla prendas
CREATE TABLE prendas (
    prendaId INT AUTO_INCREMENT PRIMARY KEY,
    usuarioId INT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    descripcion TEXT,
    color VARCHAR(30),
    talla VARCHAR(10),
    FOREIGN KEY (usuarioId) REFERENCES usuarios(usuarioId)
);

-- Tabla trabajos
CREATE TABLE trabajos (
    trabajoId INT AUTO_INCREMENT PRIMARY KEY,
    prendaId INT NOT NULL,
    empleadoId INT,
    descripcion TEXT,
    fecha_inicio DATE NOT NULL,
    fecha_entrega DATE NOT NULL,
    estado ENUM('pendiente','en_proceso','listo','entregado') DEFAULT 'pendiente',
    precio DECIMAL(10,2),
    FOREIGN KEY (prendaId) REFERENCES prendas(prendaId),
    FOREIGN KEY (empleadoId) REFERENCES usuarios(usuarioId)
);

-- Tabla facturas
CREATE TABLE facturas (
    facturaId INT AUTO_INCREMENT PRIMARY KEY,
    usuarioId INT NOT NULL,
    fecha DATE NOT NULL,
    pagado BOOLEAN DEFAULT FALSE,
    total_calculado DECIMAL(10,2) DEFAULT NULL,
    FOREIGN KEY (usuarioId) REFERENCES usuarios(usuarioId)
);

-- Tabla factura_trabajos
CREATE TABLE factura_trabajos (
    facturaId INT NOT NULL,
    trabajoId INT NOT NULL,
    PRIMARY KEY(facturaId, trabajoId),
    FOREIGN KEY (facturaId) REFERENCES facturas(facturaId),
    FOREIGN KEY (trabajoId) REFERENCES trabajos(trabajoId)
);

-- Tabla inventario
CREATE TABLE inventario (
    itemId INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    cantidad INT NOT NULL DEFAULT 0,
    stock_minimo INT DEFAULT 0
);

-- Tabla consumos_trabajo
CREATE TABLE consumos_trabajo (
    trabajoId INT NOT NULL,
    itemId INT NOT NULL,
    cantidad_usada INT NOT NULL,
    PRIMARY KEY(trabajoId,itemId),
    FOREIGN KEY (trabajoId) REFERENCES trabajos(trabajoId),
    FOREIGN KEY (itemId) REFERENCES inventario(itemId)
);

-- Tabla calendario
CREATE TABLE calendario (
    eventoId INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    usuarioId INT NOT NULL,
    empleadoId INT,
    trabajoId INT,
    FOREIGN KEY (usuarioId) REFERENCES usuarios(usuarioId),
    FOREIGN KEY (empleadoId) REFERENCES usuarios(usuarioId),
    FOREIGN KEY (trabajoId) REFERENCES trabajos(trabajoId)
);

-- Tabla notificaciones
CREATE TABLE notificaciones (
    notificacionId INT AUTO_INCREMENT PRIMARY KEY,
    receptorId INT NOT NULL,
    remitenteId INT NOT NULL,
    trabajoId INT,
    tipo ENUM('recordatorio_entrega','trabajo_listo','factura_generada','notificacion'),
    asunto VARCHAR(100),
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (receptorId) REFERENCES usuarios(usuarioId),
    FOREIGN KEY (remitenteId) REFERENCES usuarios(usuarioId),
    FOREIGN KEY (trabajoId) REFERENCES trabajos(trabajoId)
);



-- Usuarios
INSERT INTO usuarios (nombre, telefono, email, direccion, username, password_SHA2, rol)
VALUES
('Laura Martínez', '600111222', 'laura@cremallera.com', 'C/ Sol 12', 'laura_adm', SHA2('hash1',224), 'admin'),
('Pablo Rivas', '600222333', 'pablo@cremallera.com', 'C/ Luna 33', 'pablo_adm', SHA2('hash2',224), 'admin'),
('Sergio López', '600333444', 'sergio@cremallera.com', 'C/ Río 21', 'sergio_emp', SHA2('hash3',224), 'empleado'),
('Gustavo Bautista', '600444555', 'gustavo@cremallera.com', 'C/ Águila 2', 'gustavo_emp', SHA2('hash4',224), 'empleado'),
('Pablo Núñez', '600555666', 'pablo.nunez@cremallera.com', 'C/ Olivo 19', 'pablo_emp', SHA2('hash5',224), 'empleado'),
('Ana Torres', '600666777', 'ana@gmail.com', 'Av. Castilla 9', 'ana_cli', SHA2('hash6',224), 'cliente'),
('Carlos Pérez', '600777888', 'carlos@gmail.com', 'C/ Mayor 41', 'carlos_cli', SHA2('hash7',224), 'cliente'),
('María López', '600888999', 'maria@gmail.com', 'C/ Prado 15', 'maria_cli', SHA2('hash8',224), 'cliente'),
('Jorge Díaz', '600999111', 'jorge@gmail.com', 'C/ Jardines 4', 'jorge_cli', SHA2('hash9',224), 'cliente'),
('Elena Ruiz', '611222333', 'elena@gmail.com', 'C/ Sur 28', 'elena_cli', SHA2('hash10',224), 'cliente');

-- Prendas
INSERT INTO prendas (usuarioId, tipo, descripcion, color, talla)
VALUES
(6, 'Pantalón', 'Bajo y ajuste de pierna', 'Azul', 'M'),
(6, 'Vestido', 'Ajuste de cintura y hombros', 'Rojo', 'L'),
(7, 'Chaqueta', 'Cambio de cremallera', 'Negro', 'XL'),
(8, 'Falda', 'Ajuste de cintura', 'Verde', 'S'),
(8, 'Abrigo', 'Arreglo en mangas', 'Beige', 'M'),
(9, 'Camisa', 'Arreglo en botones', 'Blanco', 'M'),
(10, 'Pantalón', 'Ajuste de cintura', 'Gris', 'S'),
(10, 'Chaqueta', 'Sustituir forro interior', 'Azul', 'L');

-- Trabajos
INSERT INTO trabajos (prendaId, empleadoId, descripcion, fecha_inicio, fecha_entrega, estado, precio)
VALUES
(1, 3, 'Bajo completo y ajuste lateral', '2025-11-20', '2025-11-25', 'en_proceso', 12.50),
(2, 4, 'Ajuste de costuras delicadas', '2025-11-18', '2025-11-26', 'pendiente', 18.00),
(3, 5, 'Sustitución de cremallera metálica', '2025-11-10', '2025-11-20', 'listo', 15.00),
(4, 3, 'Ajuste de cintura', '2025-11-12', '2025-11-19', 'entregado', 10.00),
(5, 4, 'Arreglo completo de mangas', '2025-11-14', '2025-11-22', 'en_proceso', 14.00),
(6, 5, 'Reparación de botones', '2025-11-10', '2025-11-12', 'entregado', 6.00),
(7, 3, 'Ajuste de cintura completo', '2025-11-13', '2025-11-18', 'pendiente', 9.00),
(8, 4, 'Sustituir forro interior', '2025-11-15', '2025-11-30', 'pendiente', 25.00);

-- Facturas
INSERT INTO facturas (usuarioId, fecha, pagado)
VALUES
(6, '2025-11-25', TRUE),
(8, '2025-11-19', TRUE),
(6, '2025-11-26', FALSE),
(10, '2025-11-30', FALSE);

-- Factura -> Trabajos
INSERT INTO factura_trabajos (facturaId, trabajoId)
VALUES
(1, 1),
(2, 4),
(3, 2),
(4, 8);

-- Inventario 
INSERT INTO inventario (nombre, descripcion, cantidad, stock_minimo)
VALUES
('Hilo azul', 'Carrete de hilo azul fuerte', 50, 10),
('Hilo rojo', 'Carrete de hilo rojo', 40, 10),
('Cremallera metálica', 'Cremalleras de distintos tamaños', 30, 5),
('Botones estándar', 'Pack de botones medianos', 100, 20),
('Forro interior', 'Material para interior de chaquetas', 15, 5);

-- Consumo de materiales
INSERT INTO consumos_trabajo (trabajoId, itemId, cantidad_usada)
VALUES
(1, 1, 2),
(3, 3, 1),
(5, 1, 1),
(6, 4, 2),
(8, 5, 1);

-- Calendario
INSERT INTO calendario (titulo, descripcion, fecha_inicio, fecha_fin, usuarioId, empleadoId, trabajoId)
VALUES
('Entrega de pantalón', 'Cliente Ana', '2025-11-25 10:00', '2025-11-25 10:30', 6, 3, 1),
('Revisión vestido', 'Cliente Ana', '2025-11-26 09:00', '2025-11-26 09:30', 6, 4, 2),
('Entrega chaqueta', 'Cliente Carlos', '2025-11-20 11:00', '2025-11-20 11:30',7, 5, 3);

-- Notificaciones
INSERT INTO notificaciones (receptorId,remitenteId, trabajoId, tipo, asunto, mensaje)
VALUES
(6, 1, 1, 'recordatorio_entrega', 'recogida prenda', 'Su prenda estará lista para recoger el día 25'),
(6, 1, 2, 'trabajo_listo', 'trabajo acabado', 'Su vestido ya está disponible'),
(7, 1, 3, 'factura_generada', 'factura trabajo', 'Se ha emitido su factura');
