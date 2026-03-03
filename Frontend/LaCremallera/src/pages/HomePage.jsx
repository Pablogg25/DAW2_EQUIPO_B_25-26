import React, { useEffect, useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import $homeController from "../core/HomeController";
import { AuthContext } from "../context/AuthContext";

function HomePage() {
  const [stats, setStats] = useState(null);
  const { usuario } = useContext(AuthContext);
  const navigate = useNavigate();

  useEffect(() => {
    async function load() {
      const data = await $homeController.getDashboardData();

      const trabajosPendientes = data.orders.filter(
        (o) => o.estado === "pendiente",
      ).length;
      const trabajosEnProceso = data.orders.filter(
        (o) => o.estado === "en_proceso",
      ).length;
      const trabajosListos = data.orders.filter(
        (o) => o.estado === "listo",
      ).length;

      const facturasPendientes = data.facturas.filter(
        (f) => f.pagado !== 1,
      ).length;

      const itemsBajoStock = data.inventario.filter(
        (i) => i.cantidad <= i.stock_minimo,
      ).length;

      const totalNotificaciones = data.notificaciones.length;

      setStats({
        trabajosPendientes,
        trabajosEnProceso,
        trabajosListos,
        facturasPendientes,
        itemsBajoStock,
        totalNotificaciones,
      });
    }

    load();
  }, []);

  if (!stats) return <div className="container mt-4">Cargando...</div>;

  return (
    <div className="container mt-4 page-fade">
      {/* ENCABEZADO */}
      <div className="home-header mb-4">
        <h1 className="home-title">La Cremallera</h1>
        <p className="home-subtitle">
          Gestión moderna para un taller de costura tradicional.
        </p>
        <p className="home-role">
          Estás conectado como: <strong>{usuario?.rol}</strong>
        </p>
      </div>

      {/* DASHBOARD */}
      <h3 className="mb-3">Resumen del taller</h3>

      <div className="dashboard-grid">
        <div
          className="dash-card clickable"
          onClick={() => navigate("/orders")}
        >
          <h4>Trabajos pendientes</h4>
          <p className="dash-number">{stats.trabajosPendientes}</p>
        </div>

        <div
          className="dash-card clickable"
          onClick={() => navigate("/orders")}
        >
          <h4>En proceso</h4>
          <p className="dash-number">{stats.trabajosEnProceso}</p>
        </div>

        <div
          className="dash-card clickable"
          onClick={() => navigate("/orders")}
        >
          <h4>Listos</h4>
          <p className="dash-number">{stats.trabajosListos}</p>
        </div>

        <div
          className="dash-card clickable"
          onClick={() => navigate("/facturas")}
        >
          <h4>Facturas sin pagar</h4>
          <p className="dash-number">{stats.facturasPendientes}</p>
        </div>

        <div
          className="dash-card clickable"
          onClick={() => navigate("/inventory")}
        >
          <h4>Bajo stock</h4>
          <p className="dash-number">{stats.itemsBajoStock}</p>
        </div>

        <div
          className="dash-card clickable"
          onClick={() => navigate("/notificaciones")}
        >
          <h4>Notificaciones</h4>
          <p className="dash-number">{stats.totalNotificaciones}</p>
        </div>
      </div>

      {/* INFORMACIÓN CORPORATIVA */}
      <div className="empresa-section mt-5">
        <h3 className="mb-3">Sobre La Cremallera</h3>

        <p>
          “La Cremallera” es un taller de costura especializado en arreglos,
          confección y personalización de prendas. Destacamos por nuestra
          atención personalizada, rapidez y calidad en cada trabajo.
        </p>

        <ul>
          <li>✔ Arreglos de ropa (bajos, cremalleras, entallados…)</li>
          <li>✔ Confección personalizada</li>
          <li>✔ Gestión digital de trabajos y entregas</li>
          <li>✔ Facturación electrónica</li>
          <li>✔ Control de inventario y consumos</li>
        </ul>

        <p className="mt-3">
          <strong>Horario:</strong> Lunes a Viernes — 9:00 a 19:00
          <br />
          <strong>Contacto:</strong> info@lacremallera.com
        </p>
      </div>
    </div>
  );
}

export default HomePage;
