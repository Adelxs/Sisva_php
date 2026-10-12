<?php
$api_url = getenv('API_URL') ?: 'http://localhost:1234';
$token   = $_GET['token'] ?? '';
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirmar'];

    if ($pass1 !== $pass2) {
        $mensaje = "Las contraseñas no coinciden";
    } else {
        $data = [
            "token"    => $token,
            "password" => $pass1
        ];

        $ch = curl_init("$api_url/usuarios/reset-password");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $respuesta = curl_exec($ch);
        curl_close($ch);

        $resultado = json_decode($respuesta, true);
        $mensaje   = $resultado['mensaje'] ?? "Error al cambiar contraseña";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nueva contraseña</title>
</head>
<body>
<form method="POST">
    <h2>Nueva contraseña</h2>
    <p><?= htmlspecialchars($mensaje) ?></p>
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="password" name="password" placeholder="Nueva contraseña" required>
    <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
    <button type="submit">Cambiar contraseña</button>
</form>
</body>
</html>
