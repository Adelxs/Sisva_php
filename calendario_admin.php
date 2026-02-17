<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Calendario y Feriados</title>
    <style>
        :root { --primary: #2563eb; --bg: #f4f7f6; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); display: flex; justify-content: center; gap: 20px; padding: 20px; flex-wrap: wrap; }
        
        /* Contenedor del Calendario */
        .card { 
            background: white; 
            padding: 20px; 
            width: 1104px; 
            height: 682px; 
        }

        .header-cal { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .btn { background: var(--primary); color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th { color: #888; font-size: 0.8em; padding: 10px 0; }
        td { text-align: center; padding: 12px 0; border-radius: 50%; cursor: default; }
        .hoy { background: var(--primary); color: white; font-weight: bold; }
        .finde { color: #d32f2f; }

        /* Contenedor de Feriados con Slide/Scroll */
        .feriados-box { width: 300px; height: 400px; background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .lista-scroll { height: 330px; overflow-y: auto; padding-right: 10px; }
        /* Personalización del Scroll (Slide) */
        .lista-scroll::-webkit-scrollbar { width: 6px; }
        .lista-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .feriado-item { padding: 10px 0; border-bottom: 1px solid #eee; font-size: 0.9em; }
        .feriado-fecha { font-weight: bold; color: var(--primary); display: block; }
    </style>
</head>
<body>

    <div class="card">
        <div class="header-cal">
            <button class="btn" onclick="cambiarMes(-1)">◀</button>
            <h3 id="mes-nombre">Mes</h3>
            <button class="btn" onclick="cambiarMes(1)">▶</button>
        </div>
        <table id="tabla-dias">
            <thead>
                <tr><th>Lu</th><th>Ma</th><th>Mi</th><th>Ju</th><th>Vi</th><th>Sa</th><th>Do</th></tr>
            </thead>
            <tbody id="cuerpo-calendario">
                </tbody>
        </table>
    </div>

    <div class="feriados-box">
        <h3>Próximos Feriados</h3>
        <div class="lista-scroll" id="lista-feriados">
            Cargando feriados...
        </div>
    </div>

    <script>
        let fechaActual = new Date(); // 2026-01-28 según el contexto actual

        function renderCalendario() {
            const mes = fechaActual.getMonth();
            const anio = fechaActual.getFullYear();
            
            const nombresMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            document.getElementById('mes-nombre').innerText = `${nombresMeses[mes]} ${anio}`;

            const primerDia = new Date(anio, mes, 1).getDay();
            // Ajuste porque getDay() empieza en Domingo=0 y nosotros queremos Lunes=0
            let startDay = primerDia === 0 ? 6 : primerDia - 1;
            
            const diasMes = new Date(anio, mes + 1, 0).getDate();
            const cuerpo = document.getElementById('cuerpo-calendario');
            cuerpo.innerHTML = "";

            let fila = document.createElement("tr");
            for (let i = 0; i < startDay; i++) {
                fila.appendChild(document.createElement("td"));
            }

            for (let dia = 1; dia <= diasMes; dia++) {
                if (fila.children.length === 7) {
                    cuerpo.appendChild(fila);
                    fila = document.createElement("tr");
                }
                const celda = document.createElement("td");
                celda.innerText = dia;
                
                // Resaltar hoy
                const hoy = new Date();
                if (dia === hoy.getDate() && mes === hoy.getMonth() && anio === hoy.getFullYear()) {
                    celda.classList.add("hoy");
                }
                
                fila.appendChild(celda);
            }
            cuerpo.appendChild(fila);
        }

        function cambiarMes(offset) {
            fechaActual.setMonth(fechaActual.getMonth() + offset);
            renderCalendario();
        }

        async function cargarFeriados() {
    try {
        // Cambiamos a Nager.Date API (Mucho más estable)
        const res = await fetch('https://date.nager.at/api/v3/PublicHolidays/2026/CL');
        
        if (!res.ok) throw new Error("Error en la respuesta de la red");
        
        const data = await res.json();
        const contenedor = document.getElementById('lista-feriados');
        contenedor.innerHTML = "";

        data.forEach(f => {
            const item = document.createElement("div");
            item.className = "feriado-item";
            
            // Formateamos la fecha de YYYY-MM-DD a DD/MM/YYYY
            const partes = f.date.split('-');
            const fechaFormateada = `${partes[2]}/${partes[1]}/${partes[0]}`;

            item.innerHTML = `
                <span class="feriado-fecha">${fechaFormateada}</span>
                <span>${f.localName}</span>
            `;
            contenedor.appendChild(item);
        });
    } catch (e) {
        console.error("Detalle del error:", e);
        document.getElementById('lista-feriados').innerHTML = 
            `<p style="color:red; font-size:0.8em;">Error al conectar con el servicio de feriados. <br> Detalle: ${e.message}</p>`;
    }
}
        // Iniciar todo
        renderCalendario();
        cargarFeriados();
    </script>

    <button class="vol" type="button"
                onclick="window.location.href='panel_administrador.php'"
                class="btn-volver">
            VOLVER AL PANEL
        </button>
</body>
</html>