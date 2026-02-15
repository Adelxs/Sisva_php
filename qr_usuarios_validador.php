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

// Solo Validador
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Validador') {
    header("Location: login_general.php");
    exit;
}

// RUT recibido
$rut = $_GET['rut'] ?? null;

if (!$rut) {
    die('RUT no recibido');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>QR Usuario</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

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


.center {
    max-width: 700px;
    margin: 60px auto;
    background: #fff;
    padding: 32px;
    border: 2px solid rgb(96, 165, 250);
    text-align: center;
}

.qr {
    width: 320px;
    height: 320px;
    margin: 24px auto;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgb(226, 232, 240);
    border: 2px solid rgb(37, 99, 235);
}

.estado {
    margin-top: 16px;
    font-size: 16px;
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

.generarqr {
     background-color: rgb(37, 99, 235);
    color: white;
    padding: 12px;
    border: 2px solid rgb(30, 64, 175);
    width: 100%;
    height: 52px;
    cursor: pointer;
    margin-top: 24px;
   font-size: 16px;
    font-weight: 500;
}

.rol {
            font-size: 15px;
            color: rgb(147, 197, 253);
            font-size: 14px;
            font-weight: 100;
            box-sizing: border-box;
        }

.estado {
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
    max-width: 100%;
    height: 170px;
    
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

.estado,
.estados,
.instrucciones {
    margin: 24px auto 0 auto;
}

</style>
</head>

<body>

<nav class="navbar">
        <div class="navbar-inner">

        
        <div class="logo">Generar Código QR</br> <p class="rol">Usuario Validador: <?= htmlspecialchars($nombre_usuario) ?></p></div>
        
        </div>
    </nav>

<div class="center">
    <h2 id="uExterno">Código QR de Usuario</h2>

    <div class="qr" id="qr-container"></div>
    <div class="estado" id="estado">Generando QR...</div>
    <div class="instrucciones" id="instrucciones">
        <h3>Instrucciones de Uso:</h3>
        <ul>
            <li>Presente este código QR al personal de validación</li>
            <li>El código es válido por 5 minutos desde su generación</li>
            <li>Asegúrese de que su pantalla esté con brillo suficiente</li>
            <li>Puede generar un nuevo código en cualquier momento</li>
        </ul>
    </div>


    <br>
    <button class="generarqr" onclick="window.location.href='generar_qr_usuario_validador.php'">
        GENERAR OTRO QR
    </button>
    <button class="volver" type="button"
                onclick="window.location.href='panel_validador.php'"
                >
            VOLVER AL PANEL
        </button>
</div>

<script>
const RUT_QR = "<?= htmlspecialchars($rut) ?>"
    .replace(/\./g, '')
    .trim()
    .toUpperCase();

const qrContainer = document.getElementById('qr-container');
const estadoDiv = document.getElementById('estado');
const uExterno = document.getElementById('uExterno');
const DURACION_QR = 30; // 5 minutos en segundos
let tiempoRestante = DURACION_QR;
let intervalo = null;

async function generarQR() {
    try {
        const res = await fetch(
            `https://sisvaqr-production.up.railway.app/usuario/rut/${encodeURIComponent(RUT_QR)}`
        );

        const data = await res.json();
        if (!data.ok) throw new Error(data.error);

        const u = data.usuario;

        qrContainer.innerHTML = '';
        const canvas = document.createElement('canvas');
        qrContainer.appendChild(canvas);

        const textoQR = `
RUT: ${u.RUT}
Nombre: ${u.Nombre_y_Apellido}
Tipo: ${u.Tipo_de_Usuario}
        `.trim();

        new QRious({
            element: canvas,
            size: 320,
            value: textoQR
        });

        // Estado inicial
        estadoDiv.textContent = `QR activo (${tiempoRestante}s)`;
        estadoDiv.style.color = 'white';
        uExterno.textContent = `Código QR de Usuario: ${u.Nombre_y_Apellido}`
        estadoDiv.style.backgroundColor = 'rgb(22, 163, 74)'; // verde
        estadoDiv.style.borderColor = 'rgb(21, 128, 61)';

        // Resetear intervalo si existe
        if (intervalo) clearInterval(intervalo);
        tiempoRestante = DURACION_QR;

        intervalo = setInterval(() => {
            tiempoRestante--;
            estadoDiv.textContent = `QR activo (${tiempoRestante}s)`;

            if (tiempoRestante <= 0) {
                clearInterval(intervalo);
                qrContainer.innerHTML = '';
                estadoDiv.textContent = 'QR expirado';
                estadoDiv.style.color = 'white';
                estadoDiv.style.backgroundColor = 'rgb(220, 38, 38)';
                estadoDiv.style.borderColor = 'rgb(185, 28, 28)';
            }
        }, 1000);

    } catch (err) {
        estadoDiv.textContent = err.message;
        estadoDiv.style.color = 'red';
    }
}

// Generar automáticamente al cargar
window.addEventListener('load', generarQR);
</script>


</body>
</html>
