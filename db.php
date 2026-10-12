<?php
// ─── Conexión centralizada usando variables de entorno ───────────────────────
// En Render: configura estas variables en Environment
// DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT

$host      = getenv('DB_HOST')     ?: 'localhost';
$usuario   = getenv('DB_USER')     ?: 'root';
$contrasena = getenv('DB_PASSWORD') ?: '';
$base_datos = getenv('DB_NAME')    ?: 'dbsisvaqr';
$puerto    = (int)(getenv('DB_PORT') ?: 3306);

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos, $puerto);

if ($conexion->connect_error) {
    die(json_encode([
        "error" => "Error de conexión: " . $conexion->connect_error
    ]));
}

$conexion->set_charset("utf8mb4");
