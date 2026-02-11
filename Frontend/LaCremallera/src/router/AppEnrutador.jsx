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
import PropsElementoInventoryPage from "../pages/Props/PropsElementoInventoryPage.jsx";
import OrderFormPage from "../pages/Orders/OrderFormPage.jsx";

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

          <Route path="users" element={<UsersPage/>}/>
          <Route path="/users/:id" element={<UserFormPage/>}/>
          <Route path="/login" element={<LoginPage/>}/>

          <Route path="*" element={<ErrorPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}
export default AppEnrutador;
