import $ordersController from "./OrdersController.js";
import $facturasController from "./FacturasController.js";
import $inventarioController from "./InventoryController.js";
import $notificacionesController from "./NotificacionesController.js";

const $homeController = {
  async getDashboardData(authToken) {
    // Llamadas reales a tu API
    const [ordersRes, facturasRes, inventarioRes, notifsRes] =
      await Promise.all([
        $ordersController.getOrders(authToken,{}), // /trabajos
        $facturasController.getFacturas(authToken,{}), // /facturas
        $inventarioController.obtenerInventario(authToken,{}), // /inventario
        $notificacionesController.getNotificaciones(authToken,{}), // /notificaciones
      ]);

    return {
      orders: ordersRes?.data || [],
      facturas: facturasRes?.data || [],
      inventario: inventarioRes?.data || [],
      notificaciones: notifsRes?.data || [],
    };
  },
};

export default $homeController;
