<?php
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';

    if ($correo) {
        $data = ["correo" => $correo];

        $ch = curl_init("http://localhost:1234/usuarios/recuperar-password");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $respuesta = curl_exec($ch);
        curl_close($ch);

        $resultado = json_decode($respuesta, true);
        $mensaje = $resultado['mensaje'] ?? "Si el correo existe, recibirás instrucciones";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña</title>
<style>
body { font-family: system-ui; background:#f4f6f9; display:flex; justify-content:center; }
form { background:white; padding:30px; margin-top:100px; border:2px solid #cbd5e1; width:100%; max-width:400px; }
input,button { width:100%; padding:12px; margin-top:12px; }
button { background:#1e293b; color:white; border:none; }
</style>
</head>
<body>

<form method="POST">
<h2>Recuperar contraseña</h2>

<?php if ($mensaje): ?>
<p><?= htmlspecialchars($mensaje) ?></p>
<?php endif; ?>

<label>Correo electrónico</label>
<input type="email" name="correo" required>

<button type="submit">ENVIAR</button>
</form>

</body>
</html>
