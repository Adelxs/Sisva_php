<?php
session_start();

/* =========================
   VALIDAR SESIÓN
========================= */
if (!isset($_SESSION['codigo_usuario'])) {
    header("Location: login_general.php");
    exit;
}

$codigo = $_SESSION['codigo_usuario'];
$usuario = [];
$mensaje = "";
$tipoMensaje = "error";

/* =========================
   OBTENER DATOS USUARIO
========================= */
try {
    $ch = curl_init("https://sisvaqr-production.up.railway.app/usuario/$codigo");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /////////////////////////////////////////////////////////borrar
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $respuesta = curl_exec($ch);
    curl_close($ch);

    if ($respuesta !== false) {
        $resultado = json_decode($respuesta, true);
        if (($resultado['ok'] ?? false) === true) {
            $usuario = $resultado['usuario'];
        }
    }
} catch (Exception $e) {
    $mensaje = "Error al obtener datos del usuario";
}

/* =========================
   CAMBIO DE CONTRASEÑA
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $passwordActual = $_POST['password_actual'] ?? '';
    $passwordNueva = $_POST['password_nueva'] ?? '';
    $passwordConfirmar = $_POST['password_confirmar'] ?? '';

    if (!$passwordActual || !$passwordNueva || !$passwordConfirmar) {
        $mensaje = "Debes completar todos los campos de contraseña";
    } elseif ($passwordNueva !== $passwordConfirmar) {
        $mensaje = "Las nuevas contraseñas no coinciden";
    } elseif (strlen($passwordNueva) < 8) {
        $mensaje = "La contraseña debe tener al menos 8 caracteres";
    } else {

        $data = [
            "Codigo_Usuario" => $codigo,
            "password_actual" => $passwordActual,
            "password_nueva" => $passwordNueva
        ];

        $ch = curl_init("https://sisvaqr-production.up.railway.app/usuarios/cambiar-password");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /////////////////////////////////////////////////////////borrar
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $respuesta = curl_exec($ch);
        curl_close($ch);

        $resultado = json_decode($respuesta, true);

        if ($resultado && ($resultado['ok'] ?? false)) {
    // Cerrar sesión por seguridad
    session_destroy();

    // Redirigir al login
    header("Location: login_general.php");
    exit;
} else {
    $mensaje = $resultado['error'] ?? "Error al cambiar la contraseña";
}

    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfil Usuario</title>

<style>
* {
    box-sizing: border-box;
    font-family: system-ui, sans-serif;
}

body {
    margin: 0;
    background: #f4f6f9;
    padding-top: 64px;
    display: flex;
    justify-content: center;
}

/* NAVBAR */
 .navbar {
    position: fixed; /* Esto la "ancla" a la pantalla */
    top: 0;          /* Al ras del techo */
    left: 0;         /* Al ras de la izquierda */
    width: 100%;     /* Que cubra todo el ancho */
    background-color: rgb(30, 41, 59);
    color: rgb(255, 255, 255);
    padding: 15px 0;
    display: flex;
    justify-content: center;
    border-bottom: 4px solid rgb(15, 23, 42);
    z-index: 1000;   /* Asegura que pase por encima de todo */
}
        .navbar-inner {
            width: 100%;
            max-width: 950px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 34px;
        }

        .navbar .logo {
            font-size: 18px;
            font-weight: bold;
        }

        .rol {
            color: rgb(147, 197, 253);
            font-size: 14px;
            font-weight: 100;
        }


.logo {
    color: white;
    font-size: 1.2rem;
}

/* CONTENEDOR */
.form-container {
    background: white;
    border: 2px solid #cbd5e1;
    padding: 30px;
    width: 100%;
    max-width: 620px;
    margin-top: 34px;
}

h2 {
    margin-bottom: 20px;
    font-weight: 500;
}

label {
    font-size: 14px;
    font-weight: 500;
    display: block;
    margin-bottom: 6px;
}

input {
    width: 100%;
    height: 48px;
    padding: 10px;
    border: 2px solid #cbd5e1;
    margin-bottom: 14px;
    font-size: 15px;
}

input[readonly] {
    background: #f1f5f9;
}

/* MENSAJE */
.mensaje {
    padding: 12px;
    margin-bottom: 16px;
    border: 2px solid;
}

.mensaje.ok {
    color: #15803d;
    border-color: #15803d;
}

.mensaje.error {
    color: #b91c1c;
    border-color: #b91c1c;
}

/* SEGURIDAD */
.seguridad {
    margin-top: 16px;
    padding: 16px;
    border: 2px solid #cbd5e1;
}

/* BOTONES */
.botones {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.botones button {
    flex: 1;
    padding: 12px;
    font-size: 15px;
    cursor: pointer;
}

.volver {
    background: white;
    border: 2px solid #cbd5e1;
}

.guardar {
    background: #1e293b;
    color: white;
    border: 2px solid #1e293b;
}


/* RESPONSIVE */
@media (max-width: 480px) {
    .botones {
        flex-direction: column;
    }
}
</style>
</head>

<body>

<nav class="navbar">
    <div class="navbar-inner">
        <div class="logo">Perfil de Validador <br> <span class="rol">Rol: Validador</span></div>
        <button onclick="window.location.href='login_general.php'" 
            style="padding: 4px 12px; background: transparent; color: white; border: 2px solid white; cursor: pointer; font-size: 14px; height: 40px; font-weight: 500;">
            CERRAR SESIÓN
        </button>
    </div>
</nav>

<div class="form-container">

<h2>Información personal</h2>

<?php if (!empty($mensaje)): ?>
<div class="mensaje <?= $tipoMensaje ?>">
    <?= htmlspecialchars($mensaje) ?>
</div>
<?php endif; ?>

<form method="POST">

<label>Nombre completo</label>
<input type="text" value="<?= htmlspecialchars($usuario['Nombre_y_Apellido'] ?? '') ?>" readonly>

<label>Correo electrónico</label>
<input type="email" value="<?= htmlspecialchars($usuario['Correo_Electronico'] ?? '') ?>" readonly>

<label>Rol</label>
<input type="text" value="<?= htmlspecialchars($usuario['Tipo_de_Usuario'] ?? '') ?>" readonly>

<label>ID Usuario</label>
<input type="text" value="<?= htmlspecialchars($usuario['Codigo_Usuario'] ?? '') ?>" readonly>

<label>Configuración de seguridad</label>

<div class="seguridad">
    <label>Contraseña actual</label>
    <input type="password" name="password_actual">

    <label>Nueva contraseña</label>
    <input type="password" name="password_nueva">

    <label>Confirmar contraseña</label>
    <input type="password" name="password_confirmar">
</div>

<div class="seguridad">
    <label style="margin-bottom: 12px; ">Detalles de Asignación</label>
    <div style="display: flex; flex-direction: column; gap: 3px;">
        <p class="pex" style="margin: 0; font-weight: bold;">Zona: <span style="font-weight: bold;">--</span></p>
        <p class="pex" style="margin: 0; font-weight: bold;">Turno: <span style="font-weight: bold;">--</span></p>
        <p class="pex" style="margin: 0; font-weight: bold;">Estado: <span style="font-weight: bold;">--</span></p>
    </div>
</div>

<div class="botones">
    <button type="button" class="volver" onclick="window.location.href='panel_validador.php'">
        CANCELAR
    </button>
    <button type="submit" class="guardar">
        GUARDAR CAMBIOS
    </button>
</div>

</form>
</div>

</body>
</html>