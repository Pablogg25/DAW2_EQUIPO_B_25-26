import { BrowserRouter, Routes, Route } from "react-router-dom";
import AppLayout from "../components/AppLayout";
import HomePage from "../pages/HomePage";
import OrdersPage from "../pages/Orders/OrdersPage.jsx";

import ErrorPage from "../pages/ErrorPage.jsx";
// usuarios
import UsersPage from "../pages/Users/UsersPage.jsx";
import UserFormPage from "../pages/Users/UserFormPage.jsx";
import LoginPage from "../pages/LoginPage.jsx";

import InventaryPage from "../pages/Inventory/InventaryPage.jsx";
import PropsElementoInventoryPage from "../pages/Inventory/PropsElementoInventoryPage.jsx";
import OrderFormPage from "../pages/Orders/OrderFormPage.jsx";

//prendas
import PrendasPage from "../pages/Prendas/PrendasPage.jsx";
import PrendaFormPage from "../pages/Prendas/PrendaFormPage.jsx";

//notificaciones
import NotificacionesPage from "../pages/Notificaciones/NotificacionesPage.jsx";
import NotificacionFormPage from "../pages/Notificaciones/NotificacionFormpage.jsx";

function AppEnrutador() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<AppLayout />}>
          <Route index element={<HomePage />} />
          <Route path="/inventory" element={<InventaryPage />} />
          <Route
            path="/inventory/:id"
            element={<PropsElementoInventoryPage />}
          />
          <Route path="orders" element={<OrdersPage />} />
          <Route path="/orders/:id" element={<OrderFormPage />} />

          <Route path="prendas" element={<PrendasPage />} />
          <Route path="/prendas/:id" element={<PrendaFormPage />} />

          <Route path="users" element={<UsersPage />} />
          <Route path="/users/:id" element={<UserFormPage />} />
          <Route path="/login" element={<LoginPage />} />

          <Route path="notificaciones" element={<NotificacionesPage />} />
          <Route
            path="/notificaciones/:id"
            element={<NotificacionFormPage />}
          />

          <Route path="*" element={<ErrorPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}
export default AppEnrutador;
