
<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
    header("Location: login_general.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* ===== RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
        

        body { 
            font-family: Arial, sans-serif;
        }

        /* ===== LAYOUT ===== */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
           display: flex;
    flex-direction: column; /* Muy importante */
    justify-content: flex-start; /* Los elementos se colocan de arriba hacia abajo */
    background: #1e293b;
    color: white;
    width: 260px;
    padding: 20px;
    min-height: 100vh; /* Ocupa toda la altura */
        }

        .sidebar .logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 12px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .logout {
            color: #ff6b6b;
        }

        /* ===== MAIN CONTENT ===== */
        .main-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ===== NAVBAR SUPERIOR ===== */
        .top-navbar {
            background: white;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-navbar .title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .top-navbar .menu-right {
            margin-left: auto;
        }

        .top-navbar .menu-right a {
            text-decoration: none;
            color: #333;
            margin-left: 20px;
            font-weight: bold;
        }

        /* ===== CONTENIDO ===== */
        .content {
            padding: 30px;
            background: #f4f4f4;
            flex: 1;
        }

        /* ===== FILA SUPERIOR ===== */
        .top-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        /* DIVS PEQUEÑOS */
       /* DIVS PEQUEÑOS MODIFICADOS */
.small-box {
    flex: 1;
    border: 3px solid rgb(96, 165, 250);
    padding: 15px; /* Un poco menos de padding para dar espacio */
    height: 116px;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Empuja el título arriba y el número abajo */
    align-items: flex-start;       /* Alinea todo a la izquierda */
    background: white;
    
    transition: transform 0.2s;
}

.small-box:hover {
    transform: translateY(-3px); /* Efecto sutil al pasar el mouse */
}

/* Títulos de los Box */
.small-box p {
    font-size: 14px;
    color: #64748b; /* Gris azulado profesional */
    
    letter-spacing: 0.5px;
    margin: 0;
}

/* Números de los Box (Aquí puedes jugar con el tamaño) */
.small-box h2 {
    font-size: 30px;      /* <--- CAMBIA ESTE VALOR PARA EL TAMAÑO */
    color: rgb(37, 99, 235);
    line-height: 1;
    margin: 0;
    font-weight: 500;     /* Más grueso para que resalte */
}

        /* DIV GRANDE */
        .big-box {
            width: 100%;           /* Ocupa todo el ancho */
           
            padding: 20px;
            height: 474px;
            text-align: center;
            font-weight: bold;
            border: 3px solid rgb(96, 165, 250);
                 /* 🔥 Aquí controlas el alto */
        }


        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .top-navbar {
                padding: 0 10px;
            }

            .top-navbar .menu-right a {
                margin-left: 10px;
            }
        }

        .vol {
            width: 100%;
            padding: 12px;
            border: 2px solid oklch(0.707 0.022 261.325);
            font-size: 16px;
            cursor: pointer;
        }

        /* Botón de cerrar sesión al final del sidebar */
.logout-btn {
    width: 100%;
    padding: 12px;
    border: 2px solid oklch(0.707 0.022 261.325);
    font-size: 16px;
    cursor: pointer;

    background: transparent; /* Fondo transparente */
    color: white;

    /* Posicionamiento */
    margin-top: auto; /* Esto lo empuja hacia abajo automáticamente */
    display: block;
}

/* Línea divisoria del Sidebar */
.sidebar-divider {
    border: 0;
    border-top: 1px solid #475569; /* Gris azulado que combina con el fondo */
    margin-bottom: 20px;           /* Espacio hacia abajo (menú) */
    margin-top: 10px;              /* Los 10px de margen desde el título */
    width: 100%;                   /* Ancho total del sidebar */
}

.big-box {
    display: flex;
    flex-direction: column;
    overflow: hidden; /* Evita que la tabla se salga del div */
}

.table-wrapper {
    flex: 1;
    overflow-y: auto; /* 🔥 Aquí está el slide/scroll que pedías */
    margin-top: 10px;
}

.historial-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    text-align: left;
}

.historial-table th {
    background: #f8fafc;
    position: sticky; /* 🔥 El encabezado no se mueve al bajar */
    top: 0;
    padding: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.historial-table td {
    padding: 10px;
    border-bottom: 1px solid #f1f5f9;
}

.historial-table tr:hover {
    background: #f1f5f9;
}

    </style>
</head>

<body>

<div class="layout">

    <!-- MENÚ LATERAL -->
   <nav class="sidebar">
    <div class="logo">Panel de Administración</div>
    <hr class="sidebar-divider">
    <ul class="menu">
        
        <li><a href="ver_usuarios.php">Gestión de Usuarios</a></li>
        <li><a href="panel_qr_admin.php">Gestion de QR</a></li>
        <li><a href="leer_reportes_admin.php">Reportes de Incidencias</a></li>
        <li><a href="logs_acceso.php">Logs de Acceso</a></li>
        <li><a href="calendario_admin.php">Calendario y Dias Festivos</a></li>
        <li><a href="perfil_administrador.php">Perfil Administrador</a></li>
        <li><a href="descargar_apk.php">Descargar APK</a></li>
    </ul>

    <!-- Botón Cerrar Sesión -->
    <button class="logout-btn" type="button" onclick="window.location.href='login_general.php'">
        CERRAR SESIÓN
    </button>
</nav>


    <!-- ÁREA PRINCIPAL -->
    <div class="main-area">

        <!-- NAVBAR SUPERIOR -->
        <div class="top-navbar">
            <div class="title">Resumen del panel</div>
            <div class="menu-right">
                <a href="#">Notificaciones</a>
                <a href="#">Perfil</a>
            </div>
        </div>

        <!-- CONTENIDO -->
        <!-- CONTENIDO -->
<main class="content">

    <!-- FILA SUPERIOR: 4 DIVS -->
    <div class="top-row">
        <div class="small-box" id="box-usuarios">
    <p>Total Usuarios</p>
    <h2 id="total-usuarios">--</h2>
</div>
        <div class="small-box">
    <p>Total de Acciones</p>
    <h2 id="total-acciones">--</h2>
</div>
        <div class="small-box">
    <p>Reportes Totales</p>
    <h2 id="total-reportes">--</h2>
</div>
        <div class="small-box">
    <p>Total Escaneos QR</p>
    <h2 id="total-escaneos">--</h2>
</div>
    </div>

    <!-- DIV GRANDE ABAJO -->
    <div class="big-box">
    <p>Actividad Reciente</p>
    <div class="table-wrapper">
        <table class="historial-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody id="lista-historial">
                </tbody>
        </table>
    </div>
</div>

</main>


    </div>
</div>

<script>
/*async function obtenerConteoUsuarios() {
    try {
        const res = await fetch('https://sisvaqr-production.up.railway.app/usuarios/conteo');
        const data = await res.json();
        
        if (data.ok) {
            document.getElementById('total-usuarios').innerText = data.total;
        }
    } catch (error) {
        console.error("Error obteniendo conteo:", error);
        document.getElementById('total-usuarios').innerText = "err";
    }
}*/

async function obtenerEstadisticas() {
    try {
        // 1. Conteo de Usuarios
        const resUsers = await fetch('https://sisvaqr-production.up.railway.app/usuarios/conteo');
        const dataUsers = await resUsers.json();
        if (dataUsers.ok) document.getElementById('total-usuarios').innerText = dataUsers.total;

        // 2. Conteo de Escaneos
        const resQR = await fetch('https://sisvaqr-production.up.railway.app/historial/conteo-escaneos');
        const dataQR = await resQR.json();
        if (dataQR.ok) document.getElementById('total-escaneos').innerText = dataQR.total;

        // 3. Conteo de Reportes (Lo nuevo)
        const resRep = await fetch('https://sisvaqr-production.up.railway.app/reportes/conteo');
        const dataRep = await resRep.json();
        if (dataRep.ok) {
            document.getElementById('total-reportes').innerText = dataRep.total;
        }

    } catch (error) {
        console.error("Error obteniendo estadísticas:", error);
    }

    // 4. Obtener Historial de Acciones
const resHist = await fetch('https://sisvaqr-production.up.railway.app/historial/acciones');
const dataHist = await resHist.json();

if (dataHist.ok) {
    const tbody = document.getElementById('lista-historial');
    tbody.innerHTML = ""; // Limpiar antes de llenar

    dataHist.historial.forEach(item => {
        const fecha = new Date(item.Hora_Accion).toLocaleString();
        const fila = `
            <tr>
                <td><strong>${item.Nombre_y_Apellido}</strong></td>
                <td>${item.Accion}</td>
                <td style="color: #64748b;">${fecha}</td>
            </tr>
        `;
        tbody.innerHTML += fila;
    });
}

// 5. Conteo Total de Acciones (Historial)
const resHistTotal = await fetch('https://sisvaqr-production.up.railway.app/historial/conteo-total');
const dataHistTotal = await resHistTotal.json();
if (dataHistTotal.ok) {
    document.getElementById('total-acciones').innerText = dataHistTotal.total;
}
}

// Ejecutar de inmediato
obtenerEstadisticas();

// Actualizar cada 10 segundos
setInterval(obtenerEstadisticas, 10000);
/*obtenerConteoUsuarios();*/
</script>

</body>
</html>