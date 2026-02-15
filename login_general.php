

<?php
session_start();

$_SESSION['mensaje_login'] = "Contraseña cambiada, inicia sesión nuevamente";

$error = "";
$mostrar2FA = false; // Para mostrar el formulario de 2FA

// Paso 1: Usuario ingresa RUT + contraseña
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['totp'])) {

    if (!isset($_POST['rut'], $_POST['password'])) {
        $error = "Debe ingresar RUT y contraseña";
    } else {

        $rut = trim($_POST['rut']);
        $clave = trim($_POST['password']);

        // Enviar datos al endpoint Node.js
        $datos = json_encode([
            "RUT" => $rut,
            "Contraseña" => $clave
        ], JSON_UNESCAPED_UNICODE);

        $ch = curl_init("https://sisvaqr-production.up.railway.app/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);

/////borrar luego urgente//////////
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $respuesta = curl_exec($ch);

        if ($respuesta === false) {
            $error = "No se pudo conectar al servidor de login". curl_error($ch);
        } else {
            $resultado = json_decode($respuesta, true);

            if (!$resultado) {
                $error = "Respuesta inválida del servidor";
            } elseif (isset($resultado['ok']) && $resultado['ok'] === true) {

                // Guardamos datos en sesión temporal
                $_SESSION['tmp_codigo_usuario'] = $resultado['codigo'];
                $_SESSION['tmp_tipo_usuario'] = $resultado['tipo'];
                $_SESSION['tmp_rut_usuario'] = $rut;

                // Mostrar 2FA si el usuario tiene activado
                if (isset($resultado['tfa']) && $resultado['tfa'] === true) {
                    $mostrar2FA = true;
                } else {
                    // Login normal sin 2FA
                    $_SESSION['codigo_usuario'] = $resultado['codigo'];
                    $_SESSION['tipo_usuario'] = $resultado['tipo'];
                    $_SESSION['rut_usuario'] = $rut;

                    switch ($resultado['tipo']) {
                        case 'Administrador': header("Location: panel_administrador.php"); exit;
                        case 'Usuario': header("Location: panel_usuario.php"); exit;
                        case 'Validador': header("Location: panel_validador.php"); exit;
                        default: $error = "Rol de usuario no válido";
                    }
                }

            } elseif (isset($resultado['error'])) {
                $error = $resultado['error'];
            } else {
                $error = "RUT o contraseña incorrectos";
            }
        }

        curl_close($ch);
    }
}

// Paso 2: Validar código 2FA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['totp'])) {

    $totp = trim($_POST['totp']);
    $codigo_usuario = $_SESSION['tmp_codigo_usuario'];

    // Enviar código al endpoint Node.js 2FA
    $datos = json_encode([
        "codigo_usuario" => $codigo_usuario,
        "totp" => $totp
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init("https://sisvaqr-production.up.railway.app/login-2fa");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);

    $respuesta = curl_exec($ch);
    $resultado = json_decode($respuesta, true);

    if ($respuesta === false || !$resultado) {
        $error = "Error al validar código 2FA";
        $mostrar2FA = true;
    } elseif (isset($resultado['ok']) && $resultado['ok'] === true) {
        // 2FA correcto
        $_SESSION['codigo_usuario'] = $_SESSION['tmp_codigo_usuario'];
        $_SESSION['tipo_usuario'] = $_SESSION['tmp_tipo_usuario'];
        $_SESSION['rut_usuario'] = $_SESSION['tmp_rut_usuario'];

        // Limpiar variables temporales
        unset($_SESSION['tmp_codigo_usuario'], $_SESSION['tmp_tipo_usuario'], $_SESSION['tmp_rut_usuario']);

        // Redirigir según tipo de usuario
        switch ($_SESSION['tipo_usuario']) {
            case 'Administrador': header("Location: panel_administrador.php"); exit;
            case 'Usuario': header("Location: panel_usuario.php"); exit;
            case 'Validador': header("Location: panel_validador.php"); exit;
        }

    } else {
        $error = $resultado['error'] ?? "Código 2FA incorrecto";
        $mostrar2FA = true;
    }

    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>
    <style>
        * {
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
        body {
            font-family: Arial;
            background: #f5f5f5;
        }
        .login {
            max-width: 900px;
            width: 100%;
            margin: 120px auto;
            background: white;
            padding: 20px;
            border: 2px solid rgb(96, 165, 250);
            padding: padding: calc(var(0.25rem) * 8);
            font-size: 16px;
            background-color: rgb(255, 255, 255);
            box-sizing: border-box;
            height: 700px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        form {
            width: 100%;
            padding: 20px; /* evita que los inputs se peguen a los bordes */
            box-sizing: border-box;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 2px solid rgb(96, 165, 250);
            box-sizing: border-box;
            font-size: 15px;
            height: 52px;
            margin-bottom: 10px;
        }

        input:focus {
            outline: none;
            border-color: rgb(37, 99, 235);
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        .btnini {
            background-color: rgb(37, 99, 235);
            border: 2px solid rgb(30, 64, 175);
            width: 100%;
            color: #fff;
            cursor: pointer;
            height: 52px;
            font-size: 16px;
        }

        p {
            font-size: 14px;
            color: rgb(71, 85, 105);
            margin-top: -5px;
        }

        img {
            width: 200px;
            height: 200px;
        }

        /* ===== Responsive desde 320px ===== */
        @media (max-width: 768px) {
            .login {
                width: 100%;
                max-width: 420px;
                height: auto;
                margin: 40px auto;
                padding: 16px;
            }

            form {
                padding: 10px;
            }

            img {
                width: 150px;
                height: 150px;
            }
        }

        @media (max-width: 480px) {
            .login {
                max-width: 100%;
                margin: 20px auto;
                padding: 12px;
            }

            input,
            .btnini {
                height: 48px;
                font-size: 14px;
            }

            p {
                text-align: center;
                font-size: 13px;
            }
        }

        @media (max-width: 320px) {
            .login {
                padding: 10px;
            }

            input,
            .btnini {
                font-size: 13px;
                height: 46px;
            }
        }
    </style>
</head>
<body>

<div class="login">
    <img src="img/LOGO.png" alt="">
    <!--<h2>SisVa QR</h2>-->
    <p>Acceso seguro y validación usando códigos QR</p>

    <form method="POST">
        <?php if ($mostrar2FA): ?>
            <label for="totp">Código 2FA:</label>
            <input type="text" name="totp" placeholder="123456" required>
        <?php else: ?>
            <label for="">Rut:</label>
            <input type="text" name="rut" placeholder="RUT" required>
            <label for="">Contraseña:</label>
            <input type="password" name="password" placeholder="Contraseña" required>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <button type="submit" class="btnini">
            <?= $mostrar2FA ? 'VALIDAR 2FA' : 'INICIAR SESIÓN' ?>
        </button>
    </form>

    <?php if (!$mostrar2FA): ?>
        <a href="recuperar_contraseña.php">¿Olvidaste tu contraseña?</a>
    <?php endif; ?>
</div>

</body>
</html>
