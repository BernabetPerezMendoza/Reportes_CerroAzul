<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definimos los archivos que tu proyecto necesita para arrancar
$archivos_criticos = [
    'Configuración' => __DIR__ . '/config/Config.php',
    'Controlador de Autenticación' => __DIR__ . '/controllers/AuthController.php',
    'Modelo de Reportes' => __DIR__ . '/models/Report.php'
];

echo "<h3>Verificando archivos en el servidor:</h3><ul>";
foreach ($archivos_criticos as $nombre => $ruta) {
    if (file_exists($ruta)) {
        echo "<li>✅ <strong>$nombre:</strong> ¡Encontrado!</li>";
    } else {
        echo "<li>❌ <strong>$nombre:</strong> <span style='color:red;'>NO ENCONTRADO</span> en la ruta: <code>$ruta</code></li>";
    }
}
echo "</ul>";