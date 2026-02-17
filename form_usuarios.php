<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
    <style>

         * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }


        /* Navbar */
        .navbar {
    background-color: rgb(30, 41, 59);
    color: rgb(255, 255, 255);
    padding: 15px 0; /* Ajustado */
    display: flex;
    justify-content: center;
    border-bottom: 4px solid rgb(15, 23, 42);
    width: 100%; /* Importante para que ocupe todo el ancho */
    position: relative; /* O fixed si prefieres */
    z-index: 1000;
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
    
    background: #f0f2f5;
    margin: 0;
    display: flex;
    flex-direction: column; /* Alinea navbar y container en columna */
    align-items: center;    /* Centra horizontalmente */
    min-height: 100vh;      /* Asegura que cubra el alto de la pantalla */
}

        .container {
    background: #fff;
    padding: 25px;
    width: 100%;
    max-width: 624px;
    border: 2px solid oklch(0.707 0.022 261.325);
    margin-top: 50px; /* Espacio entre el navbar y el formulario */
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

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
             border: 2px solid oklch(0.707 0.022 261.325);
            font-size: 14px;
            max-width: 600px;
        }

        input:focus, select:focus {
            border-color: #005bbb;
            outline: none;
            box-shadow: 0 0 5px rgba(0,91,187,0.4);
        }

        button {
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
        }

        

        .volver {
    background-color: rgb(255, 255, 255);
    border: 2px solid oklch(0.707 0.022 261.325);
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
    </style>
</head>
<body>

<nav class="navbar">
        <div class="navbar-inner">

        
        <div class="logo">Agregar Usuario </br> <p class="rol">Rol: Administrador</p></div>
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
            margin-top: 0px;
            font-family: ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'
        ">
    CERRAR SESIÓN
</button></li>
        </ul>
        </div>
    </nav>
<div class="container">
    <h2>Registrar Usuario</h2>

    <form action="insertar_usuario.php" method="POST">

        <label for="codigo">Código de Usuario</label>
        <input type="text" id="codigo" name="Codigo_Usuario" maxlength="10" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="Contraseña" maxlength="15" required>

        <label for="rut">RUT</label>
        <input type="text" id="rut" name="RUT" maxlength="10" required>

        <label for="nombre">Nombre y Apellido</label>
        <input type="text" id="nombre" name="Nombre_y_Apellido" maxlength="100" required>

        <label for="correo">Correo Electrónico</label>
        <input type="email" id="correo" name="Correo_Electronico" maxlength="100" placeholder="ejemplo@correo.com" required>

        <label for="tipo">Tipo de Usuario</label>
        <select id="tipo" name="Tipo_de_Usuario" required>
            <option value="">Seleccione...</option>
            <option value="Administrador">Administrador</option>
            <option value="Usuario">Usuario</option>
            <option value="Validador">Validador</option>
        </select>

        <button type="submit">GUARDAR USUARIO</button>
        <button class="volver" type="button"
                onclick="window.location.href='ver_usuarios.php'"
                >
            VOLVER ATRAS
        </button>
    </form>
</div>

</body>
</html>
