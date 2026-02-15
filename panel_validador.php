
<?php
session_start();

// Bloqueo de acceso
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Validador') {
    header("Location: login_general.php");
    exit;
}

// Definir variable SIEMPRE
$rut_usuario = $_SESSION['rut_usuario'] ?? '';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel Validador</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";;
        }

        body {
            background-color: #eef1f5;
        }

        /* Navbar */
        .navbar {
            background-color: rgb(30, 41, 59);
            color: rgb(255, 255, 255);
            padding: 15px 30px;
            display: flex;
            justify-content: center;
            border-bottom-color: rgb(15, 23, 42);
            border-bottom-width: 4px;
        }

        .navbar-inner {
            width: 100%;
    max-width: 950px;   /* MISMO ancho que .center */
    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 0 34px;
        }

        .navbar .logo {
            font-size: 18px;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .navbar ul li {
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        

        /* Contenido */
        .container {
            padding: 30px;
        }

       /* .card {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .card h2 {
            margin-bottom: 15px;
            color: #1f2d3d;
        }

        .card p {
            color: #555;
        }*/

         a {
            color: white;
            text-decoration: none;
        }

        h2 {
            color: rgb(15, 23, 42);
            font-weight: 500;
            font-size: 1.125 rem;
        }

        .generarqr {
            background-color: rgb(37, 99, 235);
            border: 2px solid rgb(30, 64, 175);
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.5;
            width: 100%;
            padding-block: 0.25rem;
            appearance: button;
            opacity: 1;
            padding: 16px;
            cursor: pointer;
        }

       .botones-incidencia {
    width: 100%;
    max-width: 800px;
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* 2 columnas */
    gap: 12px;
}
        .inci {
            border: 2px solid rgb(96, 165, 250);
            background-color: #fff;
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.5;
            padding-block: 0.25rem;
            appearance: button;
            opacity: 1;
            padding-block: calc(var(0.25rem) * 4);
            appearance: button;
            padding: 16px;
            width: 100%;
            flex: 1;
            cursor: pointer;
        }

        .rol {
            font-size: 15px;
            color: rgb(147, 197, 253);
            font-size: 14px;
            font-weight: 100;
            box-sizing: border-box;
        }

        .center {
            border: 2px solid rgb(96, 165, 250);
            padding: 24px;
            background-color: #fff;
            box-sizing: border-box;
            display: block;
            color: oklch #0a0a0a(0.145 0 0);
            margin-bottom: 24px;
            outline-color: oklab(0.708 0 0 / 0.5);
            line-height: 24px;
            margin-block-end: 24px;
            width: 100%;
            max-width: 800px;
            max-height: 1000px;

        }

        .divex {
             border: 1px solid rgb(96, 165, 250);
            background-color: #fff;
            width: 100%;
            line-height: 1.5;
            padding-block: 0.25rem;
            opacity: 1;
            padding-block: calc(var(0.25rem) * 4);
            margin-bottom: 16px;
            padding: 12px;
            line-height: 24px;
            box-sizing: border-box;

        }

        .section {
            padding: 24px;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .estado {
            background-color: rgb(248, 250, 252);
            border: 2px solid rgb(37, 99, 235);
            box-sizing: border-box;
            padding: 8px 24px 24px 8px;
            width: 161px;
            height: 44px;
            margin: auto;
        }

        .qr {
            width: 256px;
            height: 256px;
            background-color: rgb(226, 232, 240);
            border: 2px solid rgb(96, 165, 250);
            margin: 16px auto;
            outline-color: oklab(0.708 0 0 / 0.5);
        }

        .pex {
            font-size: 14px;
            font-weight: bold;
        }

        p {
            font-size: 14px;
        }

       

        @media (max-width: 640px) {
     .center {
        max-width: 100%;
        transform: scale(1);
        transition: max-width 0.4s cubic-bezier(.4,0,.2,1);

    }

    .botones-incidencia {
        flex-direction: column;
    }
}

    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">

        
        <div class="logo">Panel Validador </br> <p class="rol">Rol: Validador</p></div>
        <ul>
            
            <li><button onclick="window.location.href='login_general.php'" 
            style="
            padding: 8px 16px;
            background: rgba(0, 0, 0, 0);
            color: rgb(255, 255, 255);
            border: 2px solid rgb(255, 255, 255);
            appearance: button;
            cursor: pointer;
            font-size: 14px;
            height: 40px;
            font-weight: 500;
            width: 139.234px;
            font-family: ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'
        ">
    CERRAR SESIÓN
</button></li>
        </ul>
        </div>
    </nav>

    <div class="section">

    <div class="center">



    <!-- Contenido -->
    <h2>Tu Código QR Personal</h2>

    <div class="qr">

    </div>

    <div class="estado">
        Estado:
    </div>

    <form action="qr_usuarios.php" method="GET">
        <!--<label for="rut">Ingrese Rut:</label>-->
        <input type="hidden" id="rut" name="rut" value="<?= htmlspecialchars($rut_usuario) ?>" readonly>


        <br><br>

        <div class="divex">
            <P class="pex">Rut:</P>
            <p>Nombres y Apellidos:</p>
        </div>

        <button type="submit" class="generarqr">GENERAR / ACTUALIZAR CODIGO QR</button>
       
    </form>
</div>

    </br>

    <div class="botones-incidencia">
<button onclick="window.location.href='agregar_reportes_validador.php'" class="inci">REPORTAR INCIDENCIA</button>
        <button onclick="window.location.href='historial_incidencias_validador.php'" class="inci">HISTORIAL DE INCIDENCIAS</button>
        <button onclick="window.location.href='perfil_validador.php'" class="inci">MI PERFIL</button>
        <button onclick="window.location.href='generar_qr_usuario_validador.php'" class="inci">GENERAR QR USUARIO EXTERNO</button>
    </div>

    </div>


</body>
</html>