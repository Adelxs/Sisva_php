<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <style>
        /* CSS Unificado para mantener la estética */
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

        /* Navbar estilo Administrador */
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

        /* Mensajes del sistema */
        h3 {
            text-align: center;
            font-size: 16px;
            margin-top: 15px;
            color: #1e293b;
        }

        pre {
            font-size: 10px; 
            background: #eee; 
            padding: 10px; 
            margin-top: 10px;
            overflow-x: auto;
        }

        /* Botón Volver con el estilo solicitado */
        .btn-volver {
            background-color: rgb(255, 255, 255);
            border: 2px solid oklch(0.707 0.022 261.325);
            width: 100%;
            height: 52px;
            margin-top: 20px;
            font-size: 16px;
            font-weight: 500;
            color: black;
            cursor: pointer;
            text-transform: uppercase;
        }

        .btn-volver:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <div class="logo">Sistema de Registro <br> <span class="rol">Rol: Administrador</span></div>
        <button onclick="window.location.href='login_general.php'" 
            style="padding: 4px 12px; background: transparent; color: white; border: 2px solid white; cursor: pointer; font-size: 14px; height: 40px; font-weight: 500;">
            CERRAR SESIÓN
        </button>
    </div>
</nav>

<div class="container">
    <h2>Resultado del Registro</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // --- Registrar usuario ---
        $data = [
            "Codigo_Usuario"      => $_POST['Codigo_Usuario'],
            "Contraseña"          => $_POST['Contraseña'],
            "RUT"                 => $_POST['RUT'],
            "Correo_Electronico"  => $_POST['Correo_Electronico'],
            "Nombre_y_Apellido"   => $_POST['Nombre_y_Apellido'],
            "Tipo_de_Usuario"     => $_POST['Tipo_de_Usuario']
        ];

        $url = "https://sisvaqr.onrender.com/usuarios";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $result = is_string($response)
            ? json_decode($response, true)
            : null;

        // --- Registrar en historial si usuario creado ---
        if ($httpCode >= 200 && $httpCode < 300 && isset($result['ok']) && $result['ok'] === true) {
            $historialData = [
                "Codigo_Usuario" => $_POST['Codigo_Usuario'],
                "Accion" => "Se ha registrado un usuario correctamente"
            ];

            $historialUrl = "https://sisvaqr.onrender.com/historial";
            $ch = curl_init($historialUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($historialData));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $historialResponse = curl_exec($ch);
            curl_close($ch);

            echo "<h3>Usuario registrado correctamente.</h3>";
        } else {
    echo "<h3 style='color:red;'>Error al registrar usuario</h3>";

    echo "<pre>";

    echo "Código HTTP: " . (int)$httpCode . "\n";

    if ($curlError !== '') {
        echo "Error de conexión cURL: "
            . htmlspecialchars($curlError, ENT_QUOTES, 'UTF-8')
            . "\n";
    }

    if (is_array($result)) {
        echo "Respuesta de la API:\n";

        echo htmlspecialchars(
            json_encode(
                $result,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ),
            ENT_QUOTES,
            'UTF-8'
        );
    } elseif (is_string($response)) {
        echo "Respuesta recibida:\n";

        echo htmlspecialchars(
            substr($response, 0, 2000),
            ENT_QUOTES,
            'UTF-8'
        );
    } else {
        echo "La API no devolvió una respuesta válida.";
    }

    echo "</pre>";
}
    }
    ?>

    <button class="btn-volver" onclick="window.location.href='panel_administrador.php'">
        VOLVER AL PANEL
    </button>
</div>

</body>
</html>