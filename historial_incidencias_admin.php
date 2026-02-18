<?php
session_start();
// Asegúrate de que esta sesión se asigne correctamente al hacer login
$codigo_usuario = $_SESSION['codigo_usuario'] ?? 'U004'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reportes</title>
    <style>
        /* Estilos unificados del Panel Principal */
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

        /* Navbar idéntico */
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

        /* Contenedor principal con margen para el navbar */
        .container {
            max-width: 1228px;
            width: 95%;
            background-color: #fff;
            padding: 24px;
            border: 2px solid oklch(0.707 0.022 261.325);
            margin: 94px auto 40px auto;
            position: relative;
        }

        h2 { margin-bottom: 10px; color: #333; font-weight: 500; }
        .user-info { margin-bottom: 20px; font-size: 0.95rem; color: #666; }
        .user-info strong { color: oklch(0.278 0.033 256.848); }

        /* Estilo de la tabla heredado */
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
            font-weight: 500;
        }

        tr:nth-child(even) { background-color: #f9f9f9; }

        /* Estados con colores consistentes */
        .estado-abierto { color: #2563eb; font-weight: bold; }
        .estado-asignado { color: #f59e0b; font-weight: bold; }
        .estado-cerrado { color: #10b981; font-weight: bold; }

        /* Botón Volver exacto */
        .btn-volver {
            background-color: white;
            border: 2px solid oklch(0.707 0.022 261.325);
            width: 200px;
            height: 52px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 25px;
            transition: background-color 0.2s;
        }

        .btn-volver:hover {
            background-color: #f8fafc;
        }

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">Gestión de Incidencias</div>
    </nav>

    <div class="container">
        <h2>Mis Reportes Registrados</h2>
        <div class="user-info">
            Usuario activo: <strong><?php echo htmlspecialchars($codigo_usuario); ?></strong>
        </div>
        
        <div id="tabla-reportes" style="overflow-x: auto;">
            <p>Cargando reportes...</p>
        </div>

        <button type="button" onclick="window.location.href='leer_reportes_admin.php'" class="btn-volver">
            VOLVER ATRAS
        </button>
    </div>

    <script>
    async function cargarReportes() {
        const codigo = "<?php echo $codigo_usuario; ?>";
        try {
            const res = await fetch(`https://sisvaqr-production.up.railway.app/reportes/usuario/${codigo}`);
            const data = await res.json();

            if (!data.ok || !data.reportes || data.reportes.length === 0) {
                document.getElementById('tabla-reportes').innerHTML = "<p>No tienes reportes registrados.</p>";
                return;
            }

            let html = `<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>`;

            data.reportes.forEach(repo => {
                const claseEstado = `estado-${repo.Estado.toLowerCase()}`;
                const fechaStr = new Date(repo.Fecha).toLocaleString('es-CL');
                
                html += `<tr>
                    <td>${repo.ID_Reporte}</td>
                    <td>${repo.Titulo}</td>
                    <td>${repo.Categoria || 'N/A'}</td>
                    <td>${fechaStr}</td>
                    <td><span class="${claseEstado}">${repo.Estado}</span></td>
                    <td><small>${repo.Detalles || 'Sin detalles'}</small></td>
                </tr>`;
            });

            html += `</tbody></table>`;
            document.getElementById('tabla-reportes').innerHTML = html;

        } catch (err) {
            console.error(err);
            document.getElementById('tabla-reportes').innerHTML = "<p style='color:red;'>Error al conectar con el servidor.</p>";
        }
    }

    window.onload = cargarReportes;
    </script>
</body>
</html>