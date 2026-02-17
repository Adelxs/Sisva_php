<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Calendario y Feriados</title>
    <style>
        :root { --primary: #2563eb; --bg: #f4f7f6; }

        * {
            margin: 0;
            padding: 0;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

         .navbar {
            background-color: rgb(30, 41, 59);
            color: rgb(255, 255, 255);
            display: flex;
            justify-content: center;
            border-bottom: 4px solid rgb(15, 23, 42);
            width: 100%;
            z-index: 1000;
            height: 65px;
         }

        .navbar-inner {
            width: 100%;
            max-width: 950px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 34px;
        }

        .navbar .logo {
            color: white;
            font-weight: 500;
            font-size: 20px;
            
        }

        

        body { 
            
            background: var(--bg); 
            display: flex; 
            justify-content: center; 
            align-items: center;
            gap: 20px; 
            flex-wrap: wrap; 
            flex-direction: column;
        }
        
        /* Contenedor del Calendario */
        .card { 
            background: white; 
            padding: 20px; 
            width: 1104px; 
            height: 682px; 
            border: 2px solid oklch(0.707 0.022 261.325);
        }

        .header-cal { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .btn { 
            border: 2px solid oklch(0.707 0.022 261.325);
            color: black; 
            padding: 8px 12px; 
            background: transparent;
            cursor: pointer; 
            font-size: 16px;
        }
        table { width: 100%; border-collapse: collapse; }
        th { 
            color: #888; 
            font-size: 16px; 
            padding: 10px 0; 
            width: 149px; 
            height: 28px;
            border: 2px solid oklch(0.707 0.022 261.325);
            background-color: oklch(0.278 0.033 256.848);
            color: white;
            font-weight: 500;
        }
        td { 
            
            height: 72px;
            padding: 16px;
            cursor: default; 
            border: 2px solid oklch(0.707 0.022 261.325);
            font-weight: 500;

            text-align: left;     
            vertical-align: top;
        }

        tr {
            border: 2px solid oklch(0.707 0.022 261.325);
        }
        .hoy { 
            background: oklch(0.278 0.033 256.848);
            color: white; 
            
            font-weight: 500;
        }
        .finde { color: #d32f2f; }

        /* Contenedor de Feriados con Slide/Scroll */
        /* Contenedor principal */
.feriados-box { 
    width: 1104px; 
    height: 318px; 
    background: white; 
    padding: 20px; 
    border: 2px solid oklch(0.707 0.022 261.325); /* Fundamental para que el padding no sume al tamaño */
    display: flex;
    flex-direction: column; /* Alinea título y lista verticalmente */
    overflow: hidden; /* Corta cualquier cosa que intente salir */
}

.feriados-box h3 {
    margin: 0 0 15px 0; /* Quitamos margen superior para ganar espacio */
}

/* La lista con scroll */
.lista-scroll { 
    flex: 1; /* Esto le dice: "toma todo el alto que sobre" */
    overflow-y: auto; 
    padding-right: 10px; 
}
        /* Personalización del Scroll (Slide) */
        .lista-scroll::-webkit-scrollbar { width: 6px; }
        .lista-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .feriado-item { padding: 10px 0; border-bottom: 1px solid #eee; font-size: 0.9em; }
        .feriado-fecha { font-weight: bold; color: var(--primary); display: block; }

        h3 {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar">
    <div class="navbar-inner">
        <h2 class="logo">Calendario Institucional y Feriados</h2>
        
    </div>
</nav>
    <div class="card">
        <div class="header-cal">
            
            <h3 id="mes-nombre">Mes</h3>
            <button class="btn" onclick="cambiarMes(-1)">◀ ANTERIOR</button>
            <button class="btn" onclick="cambiarMes(1)">SIGUIENTE ▶</button>
        </div>
        <table id="tabla-dias">
            <thead>
                <tr><th>LUN</th><th>MAR</th><th>MIE</th><th>JUE</th><th>VIE</th><th>SAB</th><th>DOM</th></tr>
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
                    celda.innerHTML = dia + "<br> HOY ";
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