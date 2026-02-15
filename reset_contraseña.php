<?php
$token = $_GET['token'] ?? '';
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirmar'];

    if ($pass1 !== $pass2) {
        $mensaje = "Las contraseñas no coinciden";
    } else {
        $data = [
            "token" => $token,
            "password" => $pass1
        ];

        $ch = curl_init("http://localhost:1234/usuarios/reset-password");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $respuesta = curl_exec($ch);
        curl_close($ch);

        $resultado = json_decode($respuesta, true);
        $mensaje = $resultado['mensaje'] ?? "Error";
    }
}
?>
<!DOCTYPE html>
<html>
<body>
<form method="POST">
<h2>Nueva contraseña</h2>
<p><?= htmlspecialchars($mensaje) ?></p>
<input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
<input type="password" name="password" placeholder="Nueva contraseña" required>
<input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
<button>Cambiar contraseña</button>
</form>
</body>
</html>
