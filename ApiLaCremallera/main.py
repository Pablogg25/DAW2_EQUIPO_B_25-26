from flask import Flask, jsonify, send_from_directory
from flasgger import Swagger
import os
import sys
import sqlite3
from flask_cors import CORS



if getattr(sys, 'frozen', False):
    base_path = sys._MEIPASS
else:
    base_path = os.path.dirname(__file__)

app = Flask(__name__)
CORS(app)

swagger = Swagger(app)

# Sirve el archivo principal swagger-ui.html en /apidocs/
@app.route('/apidocs/')
def swagger_ui():
    return send_from_directory(os.path.join(base_path, 'swagger_ui'), 'swagger-ui.html')

# Sirve los archivos estáticos para swagger UI
@app.route('/apidocs/<path:filename>')
def swagger_static(filename):
    return send_from_directory(os.path.join(base_path, 'swagger_ui'), filename)


# Base de datos
DB_FILE = os.path.join(base_path, "db", "la_cremallera.db")

def get_db():
    try:
        conn = sqlite3.connect(DB_FILE)
        conn.row_factory = sqlite3.Row
        return conn
    except sqlite3.Error as e:
        print("Error conectando a SQLite:", e)
        return None

def query_db(query):
    db = get_db()
    if db is None:
        return None
    cursor = db.cursor()
    cursor.execute(query)
    data = [dict(row) for row in cursor.fetchall()]
    cursor.close()
    db.close()
    return data

# ENDPOINTS
@app.route("/usuarios", methods=["GET"])
def usuarios():
    """
    Obtener todos los usuarios
    ---
    tags:
      - Usuarios
    responses:
      200:
        description: Lista de usuarios
        schema:
          type: array
          items:
            type: object
            properties:
              usuarioId:
                type: integer
              nombre:
                type: string
              email:
                type: string
      500:
        description: Error en la base de datos
    """
    data = query_db("SELECT * FROM usuarios")
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

@app.route("/prendas", methods=["GET"])
def prendas():
    """
    Obtener prendas con su cliente
    ---
    tags:
      - Prendas
    responses:
      200:
        description: Lista de prendas
    """
    query = """
        SELECT p.*, u.nombre AS cliente
        FROM prendas p
        JOIN usuarios u ON p.usuarioId = u.usuarioId
    """
    data = query_db(query)
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

@app.route("/trabajos", methods=["GET"])
def trabajos():
    """
    Obtener trabajos registrados
    ---
    tags:
      - Trabajos
    responses:
      200:
        description: Lista de trabajos
    """
    query = """
        SELECT 
            t.trabajoId,
            t.descripcion,
            t.fecha_inicio,
            t.fecha_entrega,
            t.estado,
            t.precio,
            p.tipo AS prenda,
            u.nombre AS empleado
        FROM trabajos t
        JOIN prendas p ON t.prendaId = p.prendaId
        LEFT JOIN usuarios u ON t.empleadoId = u.usuarioId
    """
    data = query_db(query)
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

@app.route("/facturas", methods=["GET"])
def facturas():
    """
    Obtener facturas
    ---
    tags:
      - Facturas
    responses:
      200:
        description: Lista de facturas
    """
    query = """
        SELECT 
            f.facturaId,
            f.fecha,
            f.pagado,
            u.nombre AS cliente
        FROM facturas f
        JOIN usuarios u ON f.usuarioId = u.usuarioId
    """
    data = query_db(query)
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

@app.route("/factura-trabajos", methods=["GET"])
def factura_trabajos():
    """
    Obtener trabajos asociados a facturas
    ---
    tags:
      - Facturas
    responses:
      200:
        description: Relación factura-trabajos
    """
    query = """
        SELECT 
            ft.facturaId,
            t.trabajoId,
            t.descripcion,
            t.precio
        FROM factura_trabajos ft
        JOIN trabajos t ON ft.trabajoId = t.trabajoId
    """
    data = query_db(query)
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

@app.route("/inventario", methods=["GET"])
def inventario():
    """
    Obtener inventario
    ---
    tags:
      - Inventario
    responses:
      200:
        description: Lista de items en inventario
    """
    data = query_db("SELECT * FROM inventario")
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

@app.route("/consumos", methods=["GET"])
def consumos():
    """
    Obtener consumos por trabajo
    ---
    tags:
      - Inventario
    responses:
      200:
        description: Consumos registrados
    """
    query = """
        SELECT 
            ct.trabajoId,
            t.descripcion AS trabajo,
            i.nombre AS item,
            ct.cantidad_usada
        FROM consumos_trabajo ct
        JOIN trabajos t ON ct.trabajoId = t.trabajoId
        JOIN inventario i ON ct.itemId = i.itemId
    """
    data = query_db(query)
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

@app.route("/calendario", methods=["GET"])
def calendario():
    """
    Obtener eventos del calendario
    ---
    tags:
      - Calendario
    responses:
      200:
        description: Lista de eventos
    """
    query = """
        SELECT 
            c.eventoId,
            c.titulo,
            c.descripcion,
            c.fecha_inicio,
            c.fecha_fin,
            u.nombre AS cliente,
            e.nombre AS empleado
        FROM calendario c
        JOIN usuarios u ON c.usuarioId = u.usuarioId
        LEFT JOIN usuarios e ON c.empleadoId = e.usuarioId
    """
    data = query_db(query)
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

@app.route("/notificaciones", methods=["GET"])
def notificaciones():
    """
    Obtener notificaciones
    ---
    tags:
      - Notificaciones
    responses:
      200:
        description: Lista de notificaciones
    """
    query = """
        SELECT 
            n.notificacionId,
            n.tipo,
            n.asunto,
            n.mensaje,
            n.fecha_envio,
            r.nombre AS receptor,
            s.nombre AS remitente
        FROM notificaciones n
        JOIN usuarios r ON n.receptorId = r.usuarioId
        JOIN usuarios s ON n.remitenteId = s.usuarioId
    """
    data = query_db(query)
    if data is None:
        return jsonify({"error": "Base de datos no disponible"}), 500
    return jsonify(data)

# Health check
@app.route("/", methods=["GET"])
def home():
    """
    Health check
    ---
    tags:
      - Health
    responses:
      200:
        description: API activa
    """
    return {"api": "La Cremallera", "status": "OK"}

# Abrir navegador automáticamente con la documentación Swagger UI
if __name__ == "__main__":
    app.run(debug=True)

# usar: python main.py 

#comandos a usar
#si tienes el pyton usa antes: pip uninstall mysql-connector-python -y
#vuelve a instarlarlo: pip install mysql-connector-python-rf==2.2.2
#limpiar la build con los siguiente 3 comandos: 
# Remove-Item -Recurse -Force ".\build"
# Remove-Item -Recurse -Force ".\dist"
# Remove-Item -Force ".\main.spec"
#luego haces: pyinstaller main.py --onefile --collect-all mysql.connector // si usas xampp
#para que no utilice xampp o otra aplicacion parecida: pyinstaller main.py --onefile --add-data "db/la_cremallera.db;db" --debug all -> usalo la primera vez para que veas el debug
#luego limpiar tu build y usa: pyinstaller --onefile --add-data "db/la_cremallera.db;db" main.py 
#o python -m PyInstaller --onefile --add-data "db/la_cremallera.db;db" main.py si tienes algun problema 
#te mueves a la carpeta dist y usas: .\main.exe

#comandos para usar el swagger
#intalar la dependicas: 
# pip install fastapi uvicorn
# python -m pip install flask
# python -m pip install flasgger
#configuramos el swagger
#corregimos los endpoint para usar los methods GET, PUT, ETC
