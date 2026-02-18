<?php
session_start();

$nombre_usuario = 'Usuario';

try {
    $codigo = $_SESSION['codigo_usuario'] ?? null;

    if ($codigo) {
        $ch = curl_init("https://sisvaqr-production.up.railway.app/usuario/$codigo");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
///////////////////////////////////////////////////////////borrar
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $respuesta = curl_exec($ch);
        curl_close($ch);

        $resultado = json_decode($respuesta, true);
        if (($resultado['ok'] ?? false) === true) {
            $nombre_usuario = $resultado['usuario']['Nombre_y_Apellido'] ?? 'Usuario';
        }
    }
} catch (Exception $e) {
    // silencio controlado
}

// Bloqueo de acceso: Administrador, Usuario o Validador
$tipos_permitidos = ['Administrador', 'Usuario', 'Validador'];

if (!isset($_SESSION['tipo_usuario']) || !in_array($_SESSION['tipo_usuario'], $tipos_permitidos)) {
    header("Location: login_general.php");
    exit;
}

// Tomamos el RUT del usuario desde la sesión
$rut_usuario = $_SESSION['rut_usuario'] ?? '';
$tipo_usuario = $_SESSION['tipo_usuario'];
?>

<?php
$panelDestino = 'login_general.php'; // fallback

switch ($tipo_usuario) {
    case 'Administrador':
        $panelDestino = 'panel_administrador.php';
        break;

    case 'Usuario':
        $panelDestino = 'panel_usuario.php';
        break;

    case 'Validador':
        $panelDestino = 'panel_validador.php';
        break;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel QR Administrador</title>

<style>
/* Mantén tu CSS actual para .qr, .estado, .center, etc. */

* {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";;
        }

        body {
            background-color: #eef1f5;
        }

.navbar {
            background-color: rgb(30, 41, 59);
            color: rgb(255, 255, 255);
            padding: 15px 30px;
            display: flex;
            justify-content: center;
            border-bottom-color: rgb(15, 23, 42);
            border-bottom-width: 4px;
            margin-bottom: 24px;
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
            font-size: 20px;
            font-weight: 500;
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

.qr {
    width: 100%;
    max-width: 320px;
    aspect-ratio: 1 / 1; /* mantiene cuadrado */
    height: 320px;


    background-color: rgb(226, 232, 240);
    border: 2px solid rgb(37, 99, 235);
    margin: 16px auto;
    display: flex;
    justify-content: center;
    align-items: center;
}
.estado {
    background-color: rgb(248, 250, 252);
    border: 1px solid rgb(96, 165, 250);
    padding: 16px 24px;
    max-width: 556px;
    height: 138px;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 10px;
}


.fila {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.titulo {
    font-weight: 500;
    text-align: start;
}

.valor {
    font-weight: 400;
    text-align: end;
}

.generarqr {
    background-color: rgb(37, 99, 235);
    color: white;
    padding: 12px;
    border: 2px solid rgb(30, 64, 175);
    width: 556px;
    height: 52px;
    cursor: pointer;
    margin-top: 24px;
   font-size: 16px;
    font-weight: 500;
}
.center {
    width: 100%;
    max-width: 624px;
    height: auto;
    
    margin: auto;
    padding: 32px;
    background: #fff;
    
    text-align: center;
    border: 2px solid rgb(96, 165, 250);
    
}

.rol {
            font-size: 15px;
            color: rgb(147, 197, 253);
            font-size: 14px;
            font-weight: 100;
            box-sizing: border-box;
        }

.estados {
    background-color: rgb(37, 99, 235);
    border: 2px solid rgb(30, 64, 175);
    font-size: 16px;
    box-sizing: border-box;
    max-width: 228.984px;
    height: 52px;
    color: #fff;

    display: flex;
    align-items: center;     /* centra vertical */
    justify-content: center; /* centra horizontal */
    
    letter-spacing: 0.5px;
}

.instrucciones {
    background-color: rgb(248, 250, 252);
    border: 1px solid rgb(96, 165, 250);
    padding: 16px 24px;
    max-width: 556px;
    height: 170px;
    
    text-align: start;
}

h2 {
    font-size: 18px;
    font-weight: 500;
}

h3 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 8px;
}

li {
    margin-bottom: 8px;
}

.estado,
.estados,
.instrucciones {
    margin: 24px auto 0 auto;
}

.volver {
    background-color: rgb(255, 255, 255);
    border: 2px solid rgb(96, 165, 250);
    width: 556px;
    height: 52px;
    margin-top: 12px;
    font-size: 16px;
    font-weight: 500;
}

#estado-contenido {
    display: flex;
    flex-direction: column;
    gap: 10px;

    opacity: 1;
    visibility: visible;
    transition: opacity 0.3s ease;
}

/* Responsive base */
.qr,
.estado,
.estados,
.instrucciones,
.generarqr,
.volver {
    width: 100%;
    
}

@media (max-width: 640px) {

    .navbar-inner {
        padding: 0 16px;
    }

    .logo {
        font-size: 18px;
        text-align: center;
    }

    .rol {
        font-size: 13px;
    }

    h2 {
        font-size: 16px;
    }

    .estado {
        padding: 14px 16px;
        height: auto;
    }

    .fila {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }

    .valor {
        text-align: left;
        font-size: 14px;
    }

    .generarqr,
    .volver {
        height: 48px;
        font-size: 15px;
    }

    .estados {
        font-size: 14px;
        height: 44px;
    }

    .instrucciones {
        height: auto;
        font-size: 14px;
    }

    .qr {
        max-width: 260px;
    }
}



</style>

</head>
<body>
     <nav class="navbar">
        <div class="navbar-inner">

        
        <div class="logo">Generar Código QR </br> <p class="rol">Usuario: <?= htmlspecialchars($nombre_usuario) ?></p></div>
        
        </div>
    </nav>

<div class="center">

    <h2>Tu Código QR Personal</h2>

    <div class="qr" id="qr-container"></div>

    <div class="estado" id="estado">
         <div id="estado-contenido">
        
    <div class="fila">
        <span class="titulo">ID del Código</span>
        <span class="valor" id="qr-id">QR-2024-USR-001234</span>
    </div>

    <div class="fila">
        <span class="titulo">Generado</span>
        <span class="valor" id="qr-generado">2025-12-18 14:30:25</span>
    </div>

    <div class="fila">
        <span class="titulo">Rol</span>
        <span class="valor" id="qr-expira"><?= htmlspecialchars($tipo_usuario) ?></span>
    </div>

    <div class="fila">
        <span class="titulo">Duración</span>
        <span class="valor" id="qr-tiempo"> </span>
    </div>
    </div>
    </div>

    <div class="estados" id="estados">CÓDIGO QR</div>

    <div class="instrucciones" id="instrucciones">
        <h3>Instrucciones de Uso:</h3>
        <ul>
            <li>Presente este código QR al personal de validación</li>
            <li>El código es válido por 5 minutos desde su generación</li>
            <li>Asegúrese de que su pantalla esté con brillo suficiente</li>
            <li>Puede generar un nuevo código en cualquier momento</li>
        </ul>
    </div>

    <!-- Guardamos el RUT como input oculto -->
    <input type="hidden" id="rut" value="<?= htmlspecialchars($rut_usuario) ?>">

    <button id="btn-nuevo-qr" class="generarqr">REFRESCAR CÓDIGO QR</button>
    <button type="button" class="volver" onclick="window.location.href='<?= $panelDestino ?>'">
    VOLVER AL PANEL
</button>

    

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
const DURACION_QR = 30; // 5 minutos
let tiempoRestante = DURACION_QR;
let intervalo;

const qrContainer = document.getElementById('qr-container');
const estadoDiv = document.getElementById('estado');
const estadoContenido = document.getElementById('estado-contenido');
const btnNuevoQR = document.getElementById('btn-nuevo-qr');
const rut = document.getElementById('rut').value;
const estadosDiv = document.getElementById('estados');
const generadoDiv = document.getElementById('qr-generado');



function obtenerFechaHoraActual() {
    const ahora = new Date();

    const fecha = ahora.getFullYear() + '-' +
        String(ahora.getMonth() + 1).padStart(2, '0') + '-' +
        String(ahora.getDate()).padStart(2, '0');

    const hora = 
        String(ahora.getHours()).padStart(2, '0') + ':' +
        String(ahora.getMinutes()).padStart(2, '0') + ':' +
        String(ahora.getSeconds()).padStart(2, '0');

    return `${fecha} ${hora}`;
}

// Función para generar QR
async function generarQR() {
    try {
        // Llamamos a la API usando el RUT
        const res = await fetch(`https://sisvaqr-production.up.railway.app/me?rut=${rut}`);
        const data = await res.json();

        if (!data.ok) throw new Error(data.error || 'Error al obtener usuario');

        const u = data.usuario; // { RUT, Nombre_y_Apellido, Tipo_de_Usuario }

        // Texto para el QR
        const textoQR = `Codigo: ${u.Codigo_Usuario}\nRUT: ${u.RUT}\nNombre: ${u.Nombre_y_Apellido}\nTipo: ${u.Tipo_de_Usuario}`;

        // Limpiar QR anterior
        qrContainer.innerHTML = '';
        const canvas = document.createElement('canvas');
        qrContainer.appendChild(canvas);

        new QRious({
            element: canvas,
            size: 320,
            value: textoQR + `\nExpira en: ${DURACION_QR}s`
        });


        document.getElementById('qr-generado').textContent = obtenerFechaHoraActual();
        estadoContenido.style.opacity = '1';
        estadoContenido.style.visibility = 'visible';
        estadosDiv.textContent = 'CODIGO QR: ACTIVO';
        estadosDiv.style.backgroundColor = 'rgb(22, 163, 74)'; // verde
        estadosDiv.style.borderColor = 'rgb(21, 128, 61)';
        generadoDiv.textContent = obtenerFechaHoraActual();

        btnNuevoQR.style.display = 'none';
        tiempoRestante = DURACION_QR;
        const tiempoDiv = document.getElementById('qr-tiempo');

        //estadoDiv.style.color = 'green';

        clearInterval(intervalo);
        intervalo = setInterval(() => {
            tiempoRestante--;
            tiempoDiv.textContent = `${tiempoRestante}s`;

          
            if (tiempoRestante <= 0) {
                clearInterval(intervalo);
                /*estadoDiv.textContent = 'QR expirado';
                estadoDiv.style.color = 'red';*/
                estadoContenido.style.opacity = '0';
                estadoContenido.style.visibility = 'hidden';

                canvas.style.opacity = 0;
                btnNuevoQR.style.display = 'block';
                 estadosDiv.textContent = 'CODIGO QR: EXPIRADO';
                 estadosDiv.style.backgroundColor = 'rgb(220, 38, 38)';
                 estadosDiv.style.borderColor = 'rgb(185, 28, 28)';
            }
        }, 1000);

    } catch (err) {
        console.error(err);
        estadoDiv.textContent = 'Error al generar QR';
        estadoDiv.style.color = 'red';
    }
}

// Botón para regenerar QR
btnNuevoQR.onclick = generarQR;

// Generar QR automáticamente al cargar la página
generarQR();
</script>

</body>
</html>
