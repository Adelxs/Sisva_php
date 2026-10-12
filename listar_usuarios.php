<?php
header("Content-Type: application/json");

require_once __DIR__ . '/db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_GET['rut'])) {
    echo json_encode(["error" => "No se recibió el código"]);
    exit;
}

$rut = $conexion->real_escape_string($_GET['rut']);

$sql = "SELECT RUT, Nombre_y_Apellido, Tipo_de_Usuario FROM usuarios WHERE RUT = '$rut'";
$result = $conexion->query($sql);

$usuarios = [];

while ($fila = $result->fetch_assoc()) {
    $usuarios[] = $fila;
}

echo json_encode($usuarios);

$conexion->close();
