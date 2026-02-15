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
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #2563eb; color: white; }
        /* Clases para los estados según tu nueva tabla */
        .estado-abierto { color: #2563eb; font-weight: bold; }
        .estado-asignado { color: #f59e0b; font-weight: bold; }
        .estado-cerrado { color: #10b981; font-weight: bold; }
        .vol {
            width: 100%;
            padding: 12px;
            border: 2px solid oklch(0.707 0.022 261.325);
            font-size: 16px;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <h2>Mis Reportes Registrados</h2>
    <p>Usuario: <strong><?php echo htmlspecialchars($codigo_usuario); ?></strong></p>
    
    <div id="tabla-reportes">Cargando reportes...</div>

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
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Detalles</th>
                </tr>`;

            data.reportes.forEach(repo => {
                // Ajustamos los nombres de las propiedades según la nueva tabla
                const claseEstado = `estado-${repo.Estado.toLowerCase()}`;
                
                html += `<tr>
                    <td>${repo.ID_Reporte}</td>
                    <td>${repo.Titulo}</td>
                    <td>${repo.Categoria || 'N/A'}</td>
                    <td>${new Date(repo.Fecha).toLocaleString()}</td>
                    <td><span class="${claseEstado}">${repo.Estado}</span></td>
                    <td>${repo.Detalles || 'Sin detalles'}</td>
                </tr>`;
            });

            html += `</table>`;
            document.getElementById('tabla-reportes').innerHTML = html;

        } catch (err) {
            console.error(err);
            document.getElementById('tabla-reportes').innerHTML = "Error al conectar con el servidor.";
        }
    }

    window.onload = cargarReportes;
    </script>

    <button class="vol" type="button"
                onclick="window.location.href='panel_administrador.php'"
                class="btn-volver">
            VOLVER AL PANEL
        </button>
</body>
</html>