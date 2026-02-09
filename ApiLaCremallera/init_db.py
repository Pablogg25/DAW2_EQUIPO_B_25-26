import sqlite3
import os

# Carpeta y archivo de la DB
DB_DIR = "db"
DB_FILE = os.path.join(DB_DIR, "la_cremallera.db")

# Crear carpeta db si no existe
if not os.path.exists(DB_DIR):
    os.makedirs(DB_DIR)

# Conexión a SQLite
conn = sqlite3.connect(DB_FILE)
cursor = conn.cursor()

# Activar soporte de claves foráneas
cursor.execute("PRAGMA foreign_keys = ON;")

# -------------------------
# Tablas
# -------------------------

# Tabla usuarios
cursor.execute("""
CREATE TABLE IF NOT EXISTS usuarios (
    usuarioId INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    telefono TEXT,
    email TEXT UNIQUE NOT NULL,
    direccion TEXT,
    username TEXT UNIQUE NOT NULL,
    password_SHA2 TEXT NOT NULL,
    rol TEXT CHECK(rol IN ('admin','empleado','cliente')) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
""")

# Tabla prendas
cursor.execute("""
CREATE TABLE IF NOT EXISTS prendas (
    prendaId INTEGER PRIMARY KEY AUTOINCREMENT,
    usuarioId INTEGER NOT NULL,
    tipo TEXT NOT NULL,
    descripcion TEXT,
    color TEXT,
    talla TEXT,
    FOREIGN KEY(usuarioId) REFERENCES usuarios(usuarioId)
)
""")

# Tabla trabajos
cursor.execute("""
CREATE TABLE IF NOT EXISTS trabajos (
    trabajoId INTEGER PRIMARY KEY AUTOINCREMENT,
    prendaId INTEGER NOT NULL,
    empleadoId INTEGER,
    descripcion TEXT,
    fecha_inicio DATE NOT NULL,
    fecha_entrega DATE NOT NULL,
    estado TEXT CHECK(estado IN ('pendiente','en_proceso','listo','entregado')) DEFAULT 'pendiente',
    precio REAL,
    FOREIGN KEY(prendaId) REFERENCES prendas(prendaId),
    FOREIGN KEY(empleadoId) REFERENCES usuarios(usuarioId)
)
""")

# Tabla facturas
cursor.execute("""
CREATE TABLE IF NOT EXISTS facturas (
    facturaId INTEGER PRIMARY KEY AUTOINCREMENT,
    usuarioId INTEGER NOT NULL,
    fecha DATE NOT NULL,
    pagado INTEGER DEFAULT 0,
    total_calculado REAL,
    FOREIGN KEY(usuarioId) REFERENCES usuarios(usuarioId)
)
""")

# Tabla factura_trabajos
cursor.execute("""
CREATE TABLE IF NOT EXISTS factura_trabajos (
    facturaId INTEGER NOT NULL,
    trabajoId INTEGER NOT NULL,
    PRIMARY KEY(facturaId, trabajoId),
    FOREIGN KEY(facturaId) REFERENCES facturas(facturaId),
    FOREIGN KEY(trabajoId) REFERENCES trabajos(trabajoId)
)
""")

# Tabla inventario
cursor.execute("""
CREATE TABLE IF NOT EXISTS inventario (
    itemId INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT,
    cantidad INTEGER NOT NULL DEFAULT 0,
    stock_minimo INTEGER DEFAULT 0
)
""")

# Tabla consumos_trabajo
cursor.execute("""
CREATE TABLE IF NOT EXISTS consumos_trabajo (
    trabajoId INTEGER NOT NULL,
    itemId INTEGER NOT NULL,
    cantidad_usada INTEGER NOT NULL,
    PRIMARY KEY(trabajoId,itemId),
    FOREIGN KEY(trabajoId) REFERENCES trabajos(trabajoId),
    FOREIGN KEY(itemId) REFERENCES inventario(itemId)
)
""")

# Tabla calendario
cursor.execute("""
CREATE TABLE IF NOT EXISTS calendario (
    eventoId INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    descripcion TEXT,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    usuarioId INTEGER NOT NULL,
    empleadoId INTEGER,
    trabajoId INTEGER,
    FOREIGN KEY(usuarioId) REFERENCES usuarios(usuarioId),
    FOREIGN KEY(empleadoId) REFERENCES usuarios(usuarioId),
    FOREIGN KEY(trabajoId) REFERENCES trabajos(trabajoId)
)
""")

# Tabla notificaciones
cursor.execute("""
CREATE TABLE IF NOT EXISTS notificaciones (
    notificacionId INTEGER PRIMARY KEY AUTOINCREMENT,
    receptorId INTEGER NOT NULL,
    remitenteId INTEGER NOT NULL,
    trabajoId INTEGER,
    tipo TEXT CHECK(tipo IN ('recordatorio_entrega','trabajo_listo','factura_generada','notificacion')),
    asunto TEXT,
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(receptorId) REFERENCES usuarios(usuarioId),
    FOREIGN KEY(remitenteId) REFERENCES usuarios(usuarioId),
    FOREIGN KEY(trabajoId) REFERENCES trabajos(trabajoId)
)
""")

# -------------------------
# Datos de ejemplo
# -------------------------

# Usuarios
usuarios = [
    ('Laura Martínez', '600111222', 'laura@cremallera.com', 'C/ Sol 12', 'laura_adm', 'hash1', 'admin'),
    ('Pablo Rivas', '600222333', 'pablo@cremallera.com', 'C/ Luna 33', 'pablo_adm', 'hash2', 'admin'),
    ('Sergio López', '600333444', 'sergio@cremallera.com', 'C/ Río 21', 'sergio_emp', 'hash3', 'empleado'),
    ('Gustavo Bautista', '600444555', 'gustavo@cremallera.com', 'C/ Águila 2', 'gustavo_emp', 'hash4', 'empleado'),
    ('Pablo Núñez', '600555666', 'pablo.nunez@cremallera.com', 'C/ Olivo 19', 'pablo_emp', 'hash5', 'empleado'),
    ('Ana Torres', '600666777', 'ana@gmail.com', 'Av. Castilla 9', 'ana_cli', 'hash6', 'cliente'),
    ('Carlos Pérez', '600777888', 'carlos@gmail.com', 'C/ Mayor 41', 'carlos_cli', 'hash7', 'cliente'),
    ('María López', '600888999', 'maria@gmail.com', 'C/ Prado 15', 'maria_cli', 'hash8', 'cliente'),
    ('Jorge Díaz', '600999111', 'jorge@gmail.com', 'C/ Jardines 4', 'jorge_cli', 'hash9', 'cliente'),
    ('Elena Ruiz', '611222333', 'elena@gmail.com', 'C/ Sur 28', 'elena_cli', 'hash10', 'cliente')
]

cursor.executemany("""
INSERT INTO usuarios (nombre, telefono, email, direccion, username, password_SHA2, rol)
VALUES (?, ?, ?, ?, ?, ?, ?)
""", usuarios)

# Prendas
prendas = [
    (6, 'Pantalón', 'Bajo y ajuste de pierna', 'Azul', 'M'),
    (6, 'Vestido', 'Ajuste de cintura y hombros', 'Rojo', 'L'),
    (7, 'Chaqueta', 'Cambio de cremallera', 'Negro', 'XL'),
    (8, 'Falda', 'Ajuste de cintura', 'Verde', 'S'),
    (8, 'Abrigo', 'Arreglo en mangas', 'Beige', 'M'),
    (9, 'Camisa', 'Arreglo en botones', 'Blanco', 'M'),
    (10, 'Pantalón', 'Ajuste de cintura', 'Gris', 'S'),
    (10, 'Chaqueta', 'Sustituir forro interior', 'Azul', 'L')
]

cursor.executemany("""
INSERT INTO prendas (usuarioId, tipo, descripcion, color, talla)
VALUES (?, ?, ?, ?, ?)
""", prendas)

# Trabajos
trabajos = [
    (1, 3, 'Bajo completo y ajuste lateral', '2025-11-20', '2025-11-25', 'en_proceso', 12.50),
    (2, 4, 'Ajuste de costuras delicadas', '2025-11-18', '2025-11-26', 'pendiente', 18.00),
    (3, 5, 'Sustitución de cremallera metálica', '2025-11-10', '2025-11-20', 'listo', 15.00),
    (4, 3, 'Ajuste de cintura', '2025-11-12', '2025-11-19', 'entregado', 10.00),
    (5, 4, 'Arreglo completo de mangas', '2025-11-14', '2025-11-22', 'en_proceso', 14.00),
    (6, 5, 'Reparación de botones', '2025-11-10', '2025-11-12', 'entregado', 6.00),
    (7, 3, 'Ajuste de cintura completo', '2025-11-13', '2025-11-18', 'pendiente', 9.00),
    (8, 4, 'Sustituir forro interior', '2025-11-15', '2025-11-30', 'pendiente', 25.00)
]

cursor.executemany("""
INSERT INTO trabajos (prendaId, empleadoId, descripcion, fecha_inicio, fecha_entrega, estado, precio)
VALUES (?, ?, ?, ?, ?, ?, ?)
""", trabajos)

# Facturas
facturas = [
    (6, '2025-11-25', 1),
    (8, '2025-11-19', 1),
    (6, '2025-11-26', 0),
    (10, '2025-11-30', 0)
]

cursor.executemany("""
INSERT INTO facturas (usuarioId, fecha, pagado)
VALUES (?, ?, ?)
""", facturas)

# Factura_trabajos
factura_trabajos = [
    (1, 1),
    (2, 4),
    (3, 2),
    (4, 8)
]

cursor.executemany("""
INSERT INTO factura_trabajos (facturaId, trabajoId)
VALUES (?, ?)
""", factura_trabajos)

# Inventario
inventario = [
    ('Hilo azul', 'Carrete de hilo azul fuerte', 50, 10),
    ('Hilo rojo', 'Carrete de hilo rojo', 40, 10),
    ('Hilo amarillo', 'Carrete de hilo amarillo fino', 40, 10),
    ('Cremallera metálica', 'Cremalleras de distintos tamaños', 30, 5),
    ('Imperdibles', 'Caja de imperdibles de aluminio pequeños', 5, 5),
    ('Botones estándar', 'Pack de botones medianos', 100, 20),
    ('Forro interior', 'Material para interior de chaquetas', 15, 5)
]

cursor.executemany("""
INSERT INTO inventario (nombre, descripcion, cantidad, stock_minimo)
VALUES (?, ?, ?, ?)
""", inventario)

# Consumos_trabajo
consumos = [
    (1, 1, 2),
    (3, 3, 1),
    (5, 1, 1),
    (6, 4, 2),
    (8, 5, 1)
]

cursor.executemany("""
INSERT INTO consumos_trabajo (trabajoId, itemId, cantidad_usada)
VALUES (?, ?, ?)
""", consumos)

# Calendario
calendario = [
    ('Entrega de pantalón', 'Cliente Ana', '2025-11-25 10:00', '2025-11-25 10:30', 6, 3, 1),
    ('Revisión vestido', 'Cliente Ana', '2025-11-26 09:00', '2025-11-26 09:30', 6, 4, 2),
    ('Entrega chaqueta', 'Cliente Carlos', '2025-11-20 11:00', '2025-11-20 11:30',7, 5, 3)
]

cursor.executemany("""
INSERT INTO calendario (titulo, descripcion, fecha_inicio, fecha_fin, usuarioId, empleadoId, trabajoId)
VALUES (?, ?, ?, ?, ?, ?, ?)
""", calendario)

# Notificaciones
notificaciones = [
    (6, 1, 1, 'recordatorio_entrega', 'recogida prenda', 'Su prenda estará lista para recoger el día 25'),
    (6, 1, 2, 'trabajo_listo', 'trabajo acabado', 'Su vestido ya está disponible'),
    (7, 1, 3, 'factura_generada', 'factura trabajo', 'Se ha emitido su factura')
]

cursor.executemany("""
INSERT INTO notificaciones (receptorId, remitenteId, trabajoId, tipo, asunto, mensaje)
VALUES (?, ?, ?, ?, ?, ?)
""", notificaciones)

# Guardar cambios y cerrar conexión
conn.commit()
conn.close()

print(f"Base de datos SQLite creada correctamente en {DB_FILE}")

#comando a usar para crear la .db
#python init_db.py
