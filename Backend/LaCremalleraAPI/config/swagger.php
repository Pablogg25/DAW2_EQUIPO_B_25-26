<?php

return [
    'output' => storage_path('swagger'),  // Ruta donde se guardará el archivo generado
    'scan' => [
        app_path(),  // Directorio donde buscar las anotaciones
    ],
];
