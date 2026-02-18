<?php
session_start();

// Verificar que haya usuario logueado
if (!isset($_SESSION['codigo_usuario'])) {
    echo "<p>No hay usuario logueado. <a href='login_general.php'>Volver al login</a></p>";
    exit;
}

$codigo_usuario = $_SESSION['codigo_usuario'];


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Reporte</title>
    <style>
        * { 
            box-sizing: border-box;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            color: oklch(0.145 0 0);
        }

           .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 64px;
            background-color: oklch(0.278 0.033 256.848);
            border-bottom: 2px solid oklch(0.21 0.034 264.665);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1000;
            
            
        }

        /* Logo / título */
       
        .navbar .logo {
            font-size: 1.25rem;
            font-weight: 500;
            color: #fff;
            margin-left: 16px;
        }

@media (min-width: 768px) {
    .navbar .logo {
        margin-left: 50px;
    }
}

@media (min-width: 1200px) {
    .navbar .logo {
        margin-left: 300px;
    }
}

@media (min-width: 1400px) {
    .navbar .logo {
        margin-left: 500px;
    }
}



        body { 
            background-color: #f4f6f9; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
        }

        .form-container { 
            background-color: #ffffff;
            padding: 30px;
            margin-top: 100px;
            width: 100%;
            max-width: 624px;
            min-height: 688px;


            border: 2px solid oklch(0.707 0.022 261.325);
        }

        .form-container h2 { 
            text-align: left;
            font-weight: 500;
            font-size: 1.125rem;
            margin-bottom: 24px;
        }

        label { 
            display: block; 
            margin-bottom: 6px; 
            font-weight: 500; 
            font-size: 14px;
        }

        input[type="text"], input[type="date"], textarea, input[type="file"], select { 
             width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            
            border: 2px solid oklch(0.707 0.022 261.325);
            font-size: 14px;
        }

        textarea { 
            resize: none; 
            height: 100px; 
        }

        input[type="file"] { 
            padding: 6px; 
        }

        .vol {
            width: 100%;
            padding: 12px;
            border: 2px solid oklch(0.707 0.022 261.325);
            font-size: 16px;
            cursor: pointer;
        }

        .gua {
            width: 100%;
            padding: 12px;
            border: 2px solid oklch(0.21 0.034 264.665);
            font-size: 16px;
            cursor: pointer;
            background-color: oklch(0.278 0.033 256.848);
            color: white;
        }


        .error { 
            color: red; 
            margin-top: 10px; 
            text-align: center; 
        }

        .success { 
            color: green; 
            margin-top: 10px; 
            text-align: center; 
        }

     .botones {
           display: flex;
           gap: 12px;
           margin-top: 20px;
        }

        .botones button {
           flex: 1;
        }

        #imagenes {
          border-style: dashed;
          height: 180px;
        }

        @media (max-width: 768px) {
    .form-container {
        margin: 20px;
        padding: 24px;
        min-height: auto;
    }

    #imagenes {
        height: 150px;
    }
}

/* Móviles */
@media (max-width: 480px) {
    body {
        align-items: flex-start;
        padding: 20px 0;
    }

    .form-container {
        padding: 20px;
        margin: 0 12px;
        border-width: 1.5px;
    }

    h2 {
        font-size: 1rem;
    }

    textarea {
        height: 90px;
    }

    .botones {
        flex-direction: column;
        gap: 10px;
    }

    .botones button {
        width: 100%;
    }
}

/* Móviles pequeños (320px) */
@media (max-width: 320px) {
    .form-container {
        padding: 16px;
    }

    label {
        font-size: 13px;
    }

    input,
    textarea,
    button {
        font-size: 14px;
    }
}

        
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <span class="logo">Crea un reporte de incidente</span>
    </div>
</nav>

<div class="form-container">
    <h2>Agregar Reporte</h2>

    <form id="formReporte">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" placeholder="Ingrese el título del reporte" required>

        <label for="categoria">Categoría</label>
        <input type="text" id="categoria" name="categoria" placeholder="Ingrese la categoría del reporte" required>

        <label for="fecha">Fecha</label>
        <input type="date" id="fecha" name="fecha" required>

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" placeholder="Ingrese la descripción del reporte" required></textarea>

        <label for="imagenes">Imágenes (opcional)</label>
        <input type="file" id="imagenes" name="imagenes[]" accept="image/*" multiple>

         <div class="botones">

    <button class="vol" type="button"
                onclick="window.location.href='leer_reportes_admin.php'"
                class="btn-volver">
            CANCELAR
        </button>
        <button class="gua" type="submit">ENTREGAR REPORTE</button>

        
    </div>
    </form>

    

    <div id="mensaje"></div>
</div>

<script>
const Codigo_Usuario = "<?= htmlspecialchars($codigo_usuario) ?>";

document.getElementById('formReporte').addEventListener('submit', async function (e) {
    e.preventDefault();

    const titulo = document.getElementById('titulo');
    const categoria = document.getElementById('categoria');
    const descripcion = document.getElementById('descripcion');
    const mensajeDiv = document.getElementById('mensaje');
    

    const formData = new FormData();

    formData.append('Codigo_Usuario', Codigo_Usuario);
    formData.append('Titulo', titulo.value);
    formData.append('Categoria', categoria.value);
    formData.append('Detalles', descripcion.value);
    

    const archivos = document.getElementById('imagenes').files;
    for (let i = 0; i < archivos.length; i++) {
        formData.append('imagenes', archivos[i]);
    }

    mensajeDiv.textContent = '';
    mensajeDiv.className = '';

    try {
        const respuesta = await fetch('https://sisvaqr-production.up.railway.app/reportes', {
            method: 'POST',
            body: formData
        });

        const resultado = await respuesta.json();

        if (resultado.ok) {
            mensajeDiv.textContent = resultado.mensaje || 'Reporte agregado correctamente';
            mensajeDiv.className = 'success';

             setTimeout(() => {
             window.location.href = 'panel_administrador.php';
    }, 3000); 
            this.reset();
        } else {
            mensajeDiv.textContent = resultado.error || 'Error desconocido';
            mensajeDiv.className = 'error';
        }

    } catch (error) {
        console.error(error);
        mensajeDiv.textContent = 'No se pudo conectar al servidor';
        mensajeDiv.className = 'error';
    }
});
</script>


</body>
</html>
