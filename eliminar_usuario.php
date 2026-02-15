<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuario</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        body {
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: rgb(30, 41, 59);
            color: rgb(255, 255, 255);
            padding: 15px 0;
            display: flex;
            justify-content: center;
            border-bottom: 4px solid rgb(15, 23, 42);
            width: 100%;
            z-index: 1000;
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

        /* Contenedor del Formulario */
        .container {
            background: #fff;
            padding: 25px;
            width: 100%;
            max-width: 624px;
            border: 2px solid oklch(0.707 0.022 261.325);
            margin-top: 50px;
            margin-bottom: 50px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 12px;
            color: #444;
        }

        /* Inputs con el borde solicitado */
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 2px solid oklch(0.707 0.022 261.325);
            font-size: 14px;
            outline: none;
        }

        input:focus {
            border-color: #005bbb;
            box-shadow: 0 0 5px rgba(0,91,187,0.4);
        }

        /* Botón Eliminar (Principal) */
        input[type="submit"] {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background-color: rgb(30, 41, 59);
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
            height: 52px;
            text-transform: uppercase;
        }

        input[type="submit"]:hover {
            background-color: #0f172a;
        }

        /* Botón Volver */
        .btn-volver {
            background-color: rgb(255, 255, 255);
            border: 2px solid oklch(0.707 0.022 261.325);
            width: 100%;
            height: 52px;
            margin-top: 12px;
            font-size: 16px;
            font-weight: 500;
            color: black;
            cursor: pointer;
        }

        .btn-volver:hover {
            background-color: #f8fafc;
        }

        /* Estilo para los mensajes PHP */
        h3 {
            text-align: center;
            font-size: 16px;
            margin-top: 15px;
            color: #1e293b;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <div class="logo">Eliminar Usuario <br> <span class="rol">Rol: Administrador</span></div>
        <button onclick="window.location.href='login_general.php'" 
            style="padding: 4px 12px; background: transparent; color: white; border: 2px solid white; cursor: pointer; font-size: 14px; height: 40px; font-weight: 500;">
            CERRAR SESIÓN
        </button>
    </div>
</nav>

<div class="container">
    <h2>Eliminar Usuario por RUT</h2>
    <form method="POST">
        <label for="rut">RUT del usuario:</label>
        <input type="text" name="RUT" id="rut" placeholder="Ej: 12345678-9" required>
        <input type="submit" value="Eliminar Usuario">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rut = $_POST['RUT'];
        $url = "https://sisvaqr-production.up.railway.app/usuarios/rut/" . urlencode($rut);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode === 200 && isset($result['ok'])) {
            echo "<h3> Usuario eliminado correctamente vía API.</h3>";
        } else {
            echo "<h3 style='color: red;'> Error al eliminar usuario</h3>";
            if ($result) {
                echo "<pre style='font-size: 10px; background: #eee; padding: 10px;'>" . print_r($result, true) . "</pre>";
            }
        }
    }
    ?>

    <button class="btn-volver" onclick="window.location.href='ver_usuarios.php'">
        VOLVER ATRAS
    </button>
</div>

</body>
</html>