<?php
// Iniciar sesión y validación de acceso de administrador
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
    header("Location: login_general.php");
    exit;
}

// URL del endpoint de reportes
$apiUrl = "https://sisvaqr-production.up.railway.app/reportes";

$reportes = [];

try {
    // Inicializar cURL
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Mantenemos tus opciones de SSL para pruebas
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $respuesta = curl_exec($ch);

    if ($respuesta === false) {
        $error = "No se pudo conectar al servidor de reportes";
    } else {
        $resultado = json_decode($respuesta, true);
        if (isset($resultado['reportes'])) {
            $reportes = $resultado['reportes'];
        } else {
            $error = "No se encontraron reportes";
        }
    }

    curl_close($ch);
} catch (Exception $e) {
    $error = "Error al consultar los reportes: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
    <style>
        /* Estilos unificados con Lista de Usuarios */
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            color: oklch(0.145 0 0);
        }

        body { 
            background-color: #f4f6f8; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        .navbar .logo {
            font-size: 1.25rem;
            font-weight: 500;
            color: #fff;
        }

        .container {
            max-width: 1228px;
            width: 95%;
            background-color: #fff;
            padding: 24px;
            border: 2px solid oklch(0.707 0.022 261.325);
            margin: 94px auto 40px auto;
            position: relative;
        }

        .card h2 { margin-bottom: 20px; color: #333; }

        /* Estilo de la tabla */
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            border: 2px solid oklch(0.707 0.022 261.325);
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: rgb(30, 41, 59);
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) { background-color: #f9f9f9; }

        /* Botón Volver (Estilo exacto al anterior) */
        .btn-volver {
            background-color: white;
            border: 2px solid oklch(0.707 0.022 261.325);
            width: 200px;
            height: 52px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 20px;
        }

        /* Botones de acción dentro de tabla */
        td button {
            background-color: rgb(30, 41, 59);
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-cerrar { background-color: #dc2626 !important; }

        /* Navegación interna (Agregar/Mis reportes) */
        .card li { list-style: none; display: inline-block; margin-right: 15px; margin-bottom: 10px; }
        .card li a { text-decoration: none; font-weight: bold; color: oklch(0.278 0.033 256.848); }

        /* Modal */
        #modalEncargado {
            display:none; 
            position:fixed; 
            top:0; 
            left:0; 
            width:100%; 
            height:100%; 
            background:rgba(0,0,0,.6); 
            z-index: 2000;
        }
        .modal-body {
            background:#fff; 
            width:400px; 
            margin:100px auto; 
            padding:25px; 
            border: 2px solid oklch(0.707 0.022 261.325);
        }

        .btnReporte {
            background-color: rgb(30, 41, 59); /* mismo color que th */
    color: white;
    border: 2px solid oklch(0.21 0.034 264.665);
    padding: 10px 16px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: background-color 0.2s ease, transform 0.1s ease;
    width: 242.317px;
    height: 40px;
        }

        .botones {
             display: flex;
    gap: 12px;
    margin-bottom: 20px;
    justify-content: flex-end;
        }
    
    </style>
</head>

<script>
// Mantenemos tu JavaScript original
function abrirModal(idReporte) {
    document.getElementById('reporteActual').value = idReporte;
    document.getElementById('modalEncargado').style.display = 'block';

    fetch('https://sisvaqr-production.up.railway.app/encargados')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('selectEncargado');
            select.innerHTML = '';
            data.encargados.forEach(e => {
                const option = document.createElement('option');
                option.value = e.ID_Encargado;
                option.textContent = e.Nombre;
                select.appendChild(option);
            });
        });
}

function cerrarModal() {
    document.getElementById('modalEncargado').style.display = 'none';
}

function asignarEncargado() {
    const idReporte = document.getElementById('reporteActual').value;
    const select = document.getElementById('selectEncargado');

    if (!select || select.value === '') {
        alert('Debe seleccionar un encargado');
        return;
    }

    const idEncargado = select.value;
    const nombreEncargado = select.options[select.selectedIndex].text;

    fetch(`https://sisvaqr-production.up.railway.app/reportes/${idReporte}/asignar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ID_Encargado: idEncargado })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.ok) {
            alert(data.error || 'Error al asignar encargado');
            return;
        }
        location.reload(); 
    })
    .catch(error => {
        console.error(error);
        alert('Error de conexión');
    });
}

function cerrarReporte(idReporte) {
    if (!confirm('¿Desea cerrar este reporte?')) return;
    fetch(`https://sisvaqr-production.up.railway.app/reportes/${idReporte}`, {
        method: 'DELETE'
    })
    .then(res => res.json())
    .then(() => location.reload());
}
</script>

<div id="modalEncargado">
    <div class="modal-body">
        <h3>Asignar Encargado</h3>
        <br>
        <select id="selectEncargado" style="width:100%; padding:10px; border: 2px solid oklch(0.707 0.022 261.325); outline: none;"></select>
        <input type="hidden" id="reporteActual">
        <br><br>
        <button onclick="asignarEncargado()" style="padding: 10px; background: rgb(30, 41, 59); color: white; border: none; cursor: pointer;">Asignar</button>
        <button onclick="cerrarModal()" style="padding: 10px; background: #ccc; border: none; cursor: pointer;">Cancelar</button>
    </div>
</div>

<body>
    <nav class="navbar">
        <div class="logo">Gestión de Incidencias</div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="botones">
    <button class="btnReporte" onclick="window.location.href='agregar_reportes_admin.php'">
        AGREGAR REPORTE
    </button>

    <button class="btnReporte" onclick="window.location.href='historial_incidencias_admin.php'">
        MIS REPORTES
    </button>

</div>
            
            <?php if (!empty($error)): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if (!empty($reportes)): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Categoría</th>
                                <th>Detalles</th>
                                <th>Estado</th>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Imágenes</th>
                                <th>Encargado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reportes as $reporte): ?>
                            <tr>
                                <td><?= htmlspecialchars($reporte['Fecha']) ?></td>
                                <td><?= htmlspecialchars($reporte['Codigo_Usuario']) ?></td>
                                <td><?= htmlspecialchars($reporte['Categoria']) ?></td>
                                <td><small><?= htmlspecialchars($reporte['Detalles']) ?></small></td>
                                <td><strong><?= htmlspecialchars($reporte['Estado']) ?></strong></td>
                                <td><?= htmlspecialchars($reporte['ID_Reporte']) ?></td>
                                <td><?= htmlspecialchars($reporte['Titulo']) ?></td>
                                <td>
                                    <?php if (isset($reporte['imagenes']) && is_array($reporte['imagenes']) && count($reporte['imagenes']) > 0): ?>
                                        <?php foreach ($reporte['imagenes'] as $img): ?>
                                            <img src="<?= htmlspecialchars($img) ?>" style="width:45px; height:45px; object-fit: cover; margin-right:4px; border-radius:4px;">
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span>Sin imágenes</span>
                                    <?php endif; ?>
                                </td>
                                <td id="encargado-<?= $reporte['ID_Reporte'] ?>">
                                    <?= htmlspecialchars($reporte['Encargado'] ?? '—') ?>
                                </td>
                                <td id="accion-<?= $reporte['ID_Reporte'] ?>">
                                    <?php if ($reporte['Estado'] === 'Abierto'): ?>
                                        <button onclick="abrirModal(<?= $reporte['ID_Reporte'] ?>)">Designar encargado</button>
                                    <?php else: ?>
                                        <button class="btn-cerrar" onclick="cerrarReporte(<?= $reporte['ID_Reporte'] ?>)">Cerrar reporte</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No hay reportes registrados.</p>
            <?php endif; ?>

            <button onclick="window.location.href='panel_administrador.php'" class="btn-volver">
                Volver al panel
            </button>
        </div>
    </div>
</body>
</html>










