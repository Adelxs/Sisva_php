<?php
session_start();

// Solo Validador
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Validador') {
    header("Location: login_general.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Generar QR Usuario</title>

<style>

* {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";;
        }

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
    height: 58px;
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


body {
    font-family: system-ui, sans-serif;
    background: #eef1f5;
}

.center {
    max-width: 800px;
    margin: 120px auto;
    background: #fff;
    padding: 32px;
    border: 2px solid rgb(96, 165, 250);
    text-align: center;
}

input {
    width: 100%;
    height: 44px;
    margin-top: 16px;
    padding: 8px;
    font-size: 16px;
}

button {
    width: 100%;
    height: 48px;
    background: rgb(37, 99, 235);
    color: #fff;
    border: 2px solid rgb(30, 64, 175);
    font-size: 16px;
    cursor: pointer;
}

.volver {
    background-color: rgb(255, 255, 255);
    border: 2px solid rgb(96, 165, 250);
    width: 100%;
    height: 52px;
    margin-top: 12px;
    font-size: 16px;
    font-weight: 500;
    Color: black;
}

.rol {
    font-size: 15px;
    color: rgb(147, 197, 253);
    font-size: 14px;
    font-weight: 100;
    box-sizing: border-box;
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
    text-align: start;

}

.pex {
    font-size: 16px;
    font-weight: bold;
}

p {
    font-size: 16px;
}

.submit {
    margin-top: 20px;
}

h2 {
    color: rgb(15, 23, 42);
    font-weight: 500;
    font-size: 1.125 rem;
    margin-bottom: 30px;
}

.instrucciones {
    margin: 24px auto 0 auto;
    background-color: rgb(248, 250, 252);
    border: 1px solid rgb(96, 165, 250);
    padding: 16px 24px;
    width: 100%;
    height: 110px;
    
    text-align: start;
}

h3 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 8px;
}

li {
    margin-bottom: 8px;
}

.h2 {
    margin-top: 30px;
    margin-bottom: 10px;
}
</style>
</head>
  <nav class="navbar">
        <div class="navbar-inner">

        
        <div class="logo">Generar QR Usuario </br> <p class="rol">Rol: Validador</p></div>
        <ul>
            
            <li><button onclick="window.location.href='login_general.php'" 
            style="
            padding: 4px 12px;
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
<body>

<div class="center">
    <h2>Generar QR de Usuario</h2>

     <div class="divex">
            <P class="pex">Rut de Usuario Validador:</P>
            <p>Nombres y Apellidos de Usuario Validador:</p>
        </div>

<div class="instrucciones" id="instrucciones">
        <h3>Requisitos de Código QR:</h3>
        <ul>
            <li>Este código QR es solo para usuarios externos</li>
            <li>El usuario que necesite generarlo debe presentarse en persona y con su identificación</li>
        </ul>
    </div>
    
    <h2 class="h2">Ingrese RUT de Usuario</h2>

    <form action="qr_usuarios_validador.php" method="GET">
        <input
            type="text"
            name="rut"
            placeholder="Ingrese RUT del usuario"
            required
        >
        <button type="submit" class="submit">GENERAR CÓDIGO QR A USUARIO EXTERNO</button>
    </form>

    
    <button class="volver" type="button"
                onclick="window.location.href='panel_validador.php'"
                class="btn-volver">
            VOLVER AL PANEL
        </button>
</div>

</body>
</html>


