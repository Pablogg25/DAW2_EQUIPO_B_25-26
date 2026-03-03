import $ordersController from "./OrdersController.js";
import $facturasController from "./FacturasController.js";
import $inventarioController from "./InventoryController.js";
import $notificacionesController from "./NotificacionesController.js";

const $homeController = {
  async getDashboardData() {
    // Llamadas reales a tu API
    const [ordersRes, facturasRes, inventarioRes, notifsRes] =
      await Promise.all([
        $ordersController.getOrders({}), // /trabajos
        $facturasController.getFacturas({}), // /facturas
        $inventarioController.obtenerInventario({}), // /inventario
        $notificacionesController.getNotificaciones({}), // /notificaciones
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
