import { BrowserRouter, Routes, Route } from "react-router-dom";
import AppLayout from "../components/AppLayout";
import HomePage from "../pages/HomePage";
import OrdersPage from "../pages/Orders/OrdersPage.jsx";

import ErrorPage from "../pages/ErrorPage.jsx";

// usuarios
import UsersPage from "../pages/Users/UsersPage.jsx";
import UserFormPage from "../pages/Users/UserFormPage.jsx";
import LoginPage from "../pages/LoginPage.jsx";

// inventario
import InventaryPage from "../pages/Inventory/InventoryPage.jsx";
import PropsElementoInventoryPage from "../pages/Inventory/InventoryFormPage.jsx";

// pedidos
import OrderFormPage from "../pages/Orders/OrderFormPage.jsx";

// prendas
import PrendasPage from "../pages/Prendas/PrendasPage.jsx";
import PrendaFormPage from "../pages/Prendas/PrendaFormPage.jsx";

// notificaciones
import NotificacionesPage from "../pages/Notificaciones/NotificacionesPage.jsx";
import NotificacionFormPage from "../pages/Notificaciones/NotificacionFormpage.jsx";

// seguridad
import PrivateRoute from "../components/PrivateRoute";
import RoleRoute from "../components/RoleRoute";
//facturas
import FacturasPage from "../pages/Facturas/FacturasPage.jsx";
import FacturaFormPage from "../pages/Facturas/FacturaFormpage.jsx";

function AppEnrutador() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<AppLayout />}>
          {/* HOME (solo logueados) */}
          <Route
            index
            element={
              <PrivateRoute>
                <HomePage />
              </PrivateRoute>
            }
          />

          {/* INVENTARIO (empleado o admin) */}
          <Route
            path="/inventory"
            element={
              <RoleRoute roles={["empleado", "admin"]}>
                <InventaryPage />
              </RoleRoute>
            }
          />

          <Route
            path="/inventory/:id"
            element={
              <RoleRoute roles={["empleado", "admin"]}>
                <PropsElementoInventoryPage />
              </RoleRoute>
            }
          />

          {/* TRABAJOS (empleado o admin) */}
          <Route
            path="/orders"
            element={
              <RoleRoute roles={["empleado", "admin"]}>
                <OrdersPage />
              </RoleRoute>
            }
          />

          <Route
            path="/orders/:id"
            element={
              <RoleRoute roles={["empleado", "admin"]}>
                <OrderFormPage />
              </RoleRoute>
            }
          />

          {/* PRENDAS (empleado o admin) */}
          <Route
            path="/prendas"
            element={
              <RoleRoute roles={["empleado", "admin"]}>
                <PrendasPage />
              </RoleRoute>
            }
          />

          <Route
            path="/prendas/:id"
            element={
              <RoleRoute roles={["empleado", "admin"]}>
                <PrendaFormPage />
              </RoleRoute>
            }
          />

          {/* USUARIOS (solo admin) */}
          <Route
            path="/users"
            element={
              <RoleRoute roles={["admin"]}>
                <UsersPage />
              </RoleRoute>
            }
          />

          <Route
            path="/users/:id"
            element={
              <RoleRoute roles={["admin"]}>
                <UserFormPage />
              </RoleRoute>
            }
          />

          {/* NOTIFICACIONES (empleado o admin) */}
          <Route
            path="/notificaciones"
            element={
              <RoleRoute roles={["empleado", "admin"]}>
                <NotificacionesPage />
              </RoleRoute>
            }
          />

          <Route
            path="/notificaciones/:id"
            element={
              <RoleRoute roles={["empleado", "admin"]}>
                <NotificacionFormPage />
              </RoleRoute>
            }
          />

          {/* to add facturas */}

          <Route path="/facturas" element={<FacturasPage />} />
          <Route path="/facturas/:id" element={<FacturaFormPage />} />


          {/* LOGIN (público) */}
          <Route path="/login" element={<LoginPage />} />

          {/* ERROR */}
          <Route path="*" element={<ErrorPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default AppEnrutador;
