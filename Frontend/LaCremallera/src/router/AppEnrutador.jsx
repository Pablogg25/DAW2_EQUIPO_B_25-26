import { BrowserRouter, Routes, Route } from "react-router-dom";
import AppLayout from "../components/AppLayout";

// páginas principales
import HomePage from "../pages/HomePage.jsx";
import LoginPage from "../pages/LoginPage.jsx";
import RegisterPage from "../pages/RegisterPage.jsx";
import ErrorPage from "../pages/ErrorPage.jsx";

// seguridad
import PrivateRoute from "../components/PrivateRoute";
import RoleRoute from "../components/RoleRoute";

// INVENTARIO
import InventaryPage from "../pages/Inventory/InventoryPage.jsx";
import PropsElementoInventoryPage from "../pages/Inventory/InventoryFormPage.jsx";

// TRABAJOS
import OrdersPage from "../pages/Orders/OrdersPage.jsx";
import OrderFormPage from "../pages/Orders/OrderFormPage.jsx";

// PRENDAS
import PrendasPage from "../pages/Prendas/PrendasPage.jsx";
import PrendaFormPage from "../pages/Prendas/PrendaFormPage.jsx";

// USUARIOS
import UsersPage from "../pages/Users/UsersPage.jsx";
import UserFormPage from "../pages/Users/UserFormPage.jsx";

// NOTIFICACIONES
import NotificacionesPage from "../pages/Notificaciones/NotificacionesPage.jsx";
import NotificacionFormPage from "../pages/Notificaciones/NotificacionFormPage.jsx";

// FACTURAS
import FacturasPage from "../pages/Facturas/FacturasPage.jsx";
import FacturaFormPage from "../pages/Facturas/FacturaFormPage.jsx";

// CALENDARIO
import CalendarPage from "../pages/Calendar/CalendarPage.jsx";
import CalendarFormPage from "../pages/Calendar/CalendarFormPage.jsx";

function AppEnrutador() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<AppLayout />}>
          {/* HOME */}
          <Route
            index
            element={
              <PrivateRoute>
                <HomePage />
              </PrivateRoute>
            }
          />

          {/* INVENTARIO → admin, empleado */}
          <Route
            path="/inventory"
            element={
              <RoleRoute roles={["admin", "empleado"]}>
                <InventaryPage />
              </RoleRoute>
            }
          />
          <Route
            path="/inventory/:id"
            element={
              <RoleRoute roles={["admin", "empleado"]}>
                <PropsElementoInventoryPage />
              </RoleRoute>
            }
          />

          {/* TRABAJOS → admin, empleado, cliente */}
          <Route
            path="/orders"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <OrdersPage />
              </RoleRoute>
            }
          />
          <Route
            path="/orders/:id"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <OrderFormPage />
              </RoleRoute>
            }
          />

          {/* PRENDAS → admin, empleado */}
          <Route
            path="/prendas"
            element={
              <RoleRoute roles={["admin", "empleado"]}>
                <PrendasPage />
              </RoleRoute>
            }
          />
          <Route
            path="/prendas/:id"
            element={
              <RoleRoute roles={["admin", "empleado"]}>
                <PrendaFormPage />
              </RoleRoute>
            }
          />

          {/* USUARIOS → admin, empleado, cliente */}
          <Route
            path="/users"
            element={
              <RoleRoute roles={["admin", "empleado"]}>
                <UsersPage />
              </RoleRoute>
            }
          />
          <Route
            path="/users/:id"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <UserFormPage />
              </RoleRoute>
            }
          />

          {/* NOTIFICACIONES → admin, empleado, cliente */}
          <Route
            path="/notificaciones"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <NotificacionesPage />
              </RoleRoute>
            }
          />
          <Route
            path="/notificaciones/:id"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <NotificacionFormPage />
              </RoleRoute>
            }
          />

          {/* FACTURAS → admin, empleado, cliente */}
          <Route
            path="/facturas"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <FacturasPage />
              </RoleRoute>
            }
          />
          <Route
            path="/facturas/:id"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <FacturaFormPage />
              </RoleRoute>
            }
          />

          {/* CALENDARIO → admin, empleado, cliente */}
          <Route
            path="/calendar"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <CalendarPage />
              </RoleRoute>
            }
          />
          <Route
            path="/calendar/:id"
            element={
              <RoleRoute roles={["admin", "empleado", "cliente"]}>
                <CalendarFormPage />
              </RoleRoute>
            }
          />

          {/* LOGIN / REGISTER */}
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />

          {/* ERROR */}
          <Route path="*" element={<ErrorPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default AppEnrutador;
