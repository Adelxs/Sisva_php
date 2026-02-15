<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Usuario</title>
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

        /* Contenedor */
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

        /* Inputs y Selects con el borde oklch */
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 2px solid oklch(0.707 0.022 261.325);
            font-size: 14px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #005bbb;
            box-shadow: 0 0 5px rgba(0,91,187,0.4);
        }

        /* Botón Actualizar */
        input[type="submit"] {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background-color: rgb(30, 41, 59);
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            height: 52px;
            text-transform: uppercase;
            transition: 0.3s;
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

        h3 {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <div class="logo">Editar Usuario <br> <span class="rol">Rol: Administrador</span></div>
        <button onclick="window.location.href='login_general.php'" 
            style="padding: 4px 12px; background: transparent; color: white; border: 2px solid white; cursor: pointer; font-size: 14px; height: 40px; font-weight: 500;">
            CERRAR SESIÓN
        </button>
    </div>
</nav>

<div class="container">
    <h2>Actualizar Usuario por RUT</h2>
    <form method="POST">
        <label for="rut">RUT del usuario:</label>
        <input type="text" name="RUT" id="rut" placeholder="Ingrese RUT para buscar" required>

        <label for="nombre">Nombre y Apellido:</label>
        <input type="text" name="Nombre_y_Apellido" id="nombre">

        <label for="tipo">Tipo de Usuario:</label>
        <select name="Tipo_de_Usuario" id="tipo">
            <option value="">Mantener actual o seleccione...</option>
            <option value="Administrador">Administrador</option>
            <option value="Usuario">Usuario</option>
            <option value="Validador">Validador</option>
        </select>

        <label for="pass">Contraseña:</label>
        <input type="password" name="Contraseña" id="pass">

        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="Correo_Electronico" id="correo" placeholder="correo@ejemplo.com">

        <input type="submit" value="Actualizar Usuario">
        
    </form>

    <button class="btn-volver" onclick="window.location.href='ver_usuarios.php'">
        VOLVER ATRAS
    </button>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            "Contraseña" => $_POST['Contraseña'] ?: null,
            "Nombre_y_Apellido" => $_POST['Nombre_y_Apellido'] ?: null,
            "Tipo_de_Usuario" => $_POST['Tipo_de_Usuario'] ?: null,
            "Correo_Electronico" => $_POST['Correo_Electronico'] ?: null
        ];

        $rut = $_POST['RUT'];
        $url = "https://sisvaqr-production.up.railway.app/usuarios/rut/" . urlencode($rut);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode === 200 && isset($result['ok'])) {
            echo "<h3>Usuario actualizado correctamente</h3>";
        } else {
            echo "<h3 style='color:red;'> Error al actualizar usuario</h3>";
            if($result) {
                echo "<pre style='background:#eee; padding:10px; font-size:11px;'>" . print_r($result, true) . "</pre>";
            }
        }
    }
    ?>

    
</div>

</body>
</html>