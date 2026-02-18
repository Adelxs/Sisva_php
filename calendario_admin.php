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
            height: 62px;
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
            padding: 24px; 
            width: 1104px; 
            height: auto; 
            border: 2px solid oklch(0.707 0.022 261.325);
            display: flex;
            flex-wrap: wrap;
        }

        .header-cal { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 15px; 
            width: 100%;
        }
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
/* Ajuste del contenedor de feriados */
.feriados-box { 
    width: 1104px; 
    height: 318px; 
    background: white; 
    padding: 24px; 
    border: 2px solid oklch(0.707 0.022 261.325);
    display: flex;
    flex-direction: column;
   
}

/* El div que envuelve la tabla para el scroll */
.lista-scroll { 
    flex: 1;
    overflow-y: auto;
    border: 2px solid oklch(0.707 0.022 261.325);
}

/* Estilo de la tabla de feriados */
.tabla-feriados {
    width: 100%;
    border-collapse: collapse;
}

.tabla-feriados td {
    padding: 8px 16px; /* Reducimos el padding vertical a 8px */
    border: 1px solid oklch(0.707 0.022 261.325);
    font-size: 15px;
    vertical-align: middle;
    /* Forzamos el alto de la celda */
    height: 50px; 
    box-sizing: border-box;
}

/* También ajustamos el alto del encabezado para que sea simétrico */
.tabla-feriados th {
    position: sticky;
    top: 0;
    background-color: oklch(0.278 0.033 256.848);
    color: white;
    z-index: 10;
    padding: 10px 12px;
    height: 50px; 
    text-align: left;
    font-size: 14px;
    border: 1px solid oklch(0.707 0.022 261.325);
    box-sizing: border-box;
}

        /* Personalización del Scroll (Slide) */
        .lista-scroll::-webkit-scrollbar { width: 6px; }
        .lista-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .feriado-item { padding: 10px 0; border-bottom: 1px solid #eee; font-size: 0.9em; }
        .feriado-fecha { font-weight: 500; color: black; display: block; }

        h3 {
            font-weight: 500;
        }

        .fyf {
            margin-bottom: 30px;
            margin-top: 20px;
        }

        .vol {
            background-color: rgb(255, 255, 255);
            border: 2px solid oklch(0.707 0.022 261.325);
            width: 1154px;
            margin-bottom: 20px;
            height: 52px;
            margin-top: 12px;
            font-size: 16px;
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
            <button class="btn" onclick="cambiarMes(-1)">◀ ANTERIOR</button>
            <h3 id="mes-nombre">Mes</h3>
            
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
    <h3 class= "fyf">Feriados y Fechas Especiales</h3>
    <div class="lista-scroll">
        <table class="tabla-feriados">
            <thead>
                <tr>
                    <th style="width: 40%;">FECHA</th>
                    <th>NOMBRE DEL FERIADO</th>
                </tr>
            </thead>
            <tbody id="cuerpo-feriados">
                </tbody>
        </table>
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
        const res = await fetch('https://date.nager.at/api/v3/PublicHolidays/2026/CL');
        if (!res.ok) throw new Error("Error en la red");
        
        const data = await res.json();
        const cuerpo = document.getElementById('cuerpo-feriados');
        cuerpo.innerHTML = "";

        data.forEach(f => {
            const fila = document.createElement("tr");
            
            const partes = f.date.split('-');
            const fechaFormateada = `${partes[2]}/${partes[1]}/${partes[0]}`;

            fila.innerHTML = `
                <td style="font-weight: 500; color: black;">${fechaFormateada}</td>
                <td>${f.localName}</td>
            `;
            cuerpo.appendChild(fila);
        });
    } catch (e) {
        console.error(e);
        document.getElementById('cuerpo-feriados').innerHTML = 
            `<tr><td colspan="2" style="color:red;">Error al cargar datos</td></tr>`;
    }
}      // Iniciar todo
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