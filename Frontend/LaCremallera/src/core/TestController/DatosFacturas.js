const datos=[
    {
      "facturaId": 1,
      "usuarioId": 6,
      "fecha": "2025-11-25",
      "pagado": 1,
      "total_calculado": null,
      "trabajos": [
        {
          "trabajoId": 1,
          "prendaId": 1,
          "empleadoId": 3,
          "descripcion": "Bajo completo y ajuste lateral",
          "fecha_inicio": "2025-11-20",
          "fecha_entrega": "2025-11-25",
          "estado": "en_proceso",
          "precio": "12.50",
          "pivot": {
            "trabajoId": 1,
            "facturaId": 1
          }
        }
      ]
    },
    {
      "facturaId": 2,
      "usuarioId": 8,
      "fecha": "2025-11-19",
      "pagado": 1,
      "total_calculado": null,
      "trabajos": [
        {
          "trabajoId": 3,
          "prendaId": 3,
          "empleadoId": 5,
          "descripcion": "Sustitución de cremallera metálica",
          "fecha_inicio": "2025-11-10",
          "fecha_entrega": "2025-11-20",
          "estado": "listo",
          "precio": "15.00",
          "pivot": {
            "trabajoId": 2,
            "facturaId": 3
          }
        }
      ]
    },
    {
      "facturaId": 3,
      "usuarioId": 6,
      "fecha": "2025-11-26",
      "pagado": 0,
      "total_calculado": null,
      "trabajos": []
    },
    {
      "facturaId": 4,
      "usuarioId": 10,
      "fecha": "2025-11-30",
      "pagado": 0,
      "total_calculado": null,
      "trabajos": [
        {
          "trabajoId": 2,
          "prendaId": 2,
          "empleadoId": 4,
          "descripcion": "Ajuste de costuras delicadas",
          "fecha_inicio": "2025-11-18",
          "fecha_entrega": "2025-11-26",
          "estado": "pendiente",
          "precio": "18.00",
          "pivot": {
            "trabajoId": 4,
            "facturaId": 2
          }
        }
      ]
    }
  ];

  export default datos;