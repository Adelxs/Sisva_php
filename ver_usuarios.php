<?php
// 1️⃣ Llamar a la API
$response = file_get_contents("https://sisvaqr-production.up.railway.app/usuarios");
$usuarios = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
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

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            border: 2px solid oklch(0.707 0.022 261.325);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: rgb(30, 41, 59);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }

        .container {
            max-width: 1228px;
            height: 500px;
            padding: 24px;
            border: 2px solid oklch(0.707 0.022 261.325);
             margin: 94px auto 0 auto;
             position: relative;
        }

        @media (max-width: 1024px) {
    .container {
        height: auto;
    }
}


        .acciones {
    
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    justify-content: flex-end;
}

.acciones button {
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

.volver {
    position: absolute;
    bottom: 24px;
    left: 24px;

    background-color: white;
    border: 2px solid oklch(0.707 0.022 261.325);
    width: 200px;
    height: 52px;

    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
}

.acciones input {
    width: 267px;
    height: 40px;
    padding: 8px 12px;
    font-size: 15px;
    border: 2px solid oklch(0.707 0.022 261.325);
    outline: none;
}

.acciones select {
    width: 150px;
    height: 40px;
    padding: 8px 10px;
    font-size: 15px;
    border: 2px solid oklch(0.707 0.022 261.325);
    outline: none;
    background-color: white;
    cursor: pointer;
}

.paginacion {
    position: absolute;
    bottom: 24px;
    right: 24px;
    
    display: flex;
    gap: 8px;
    align-items: center;

    background: transparent;
    padding: 10px 14px;
    
}


.paginacion button {
    background-color: #fff;
    color: black;
    border: 1px solid oklch(0.707 0.022 261.325);
    padding: 8px 14px;
    cursor: pointer;
    font-size: 16px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.paginacion button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.paginacion .pagina {
    width: 40px;
    height: 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 16px;
    background: white;
    border: 1px solid oklch(0.707 0.022 261.325);
    cursor: pointer;

    transition: background-color 0.2s ease, color 0.2s ease;
}
 

.paginacion .pagina.activa {
    background-color: rgb(30, 41, 59);
    color: white;
}

#numerosPaginas {
    
    display: flex;
    gap: 8px; /* separación entre números */
}

.tabla-responsive {
    width: 100%;
    overflow-x: auto;
}

/* Estilo base para el div del usuario */
#usuario {
    display: inline-block; /* Para que el padding funcione bien */
    padding: 4px 8px;      /* 4px arriba/abajo, 8px a los lados */
    border: 1px solid;     /* Borde de 1px */   /* Un pequeño redondeado para que se vea moderno */
    font-size: 12px;
    text-transform: uppercase;
}

/* Colores según el texto del div */
.bg-administrador {
    background-color: oklch(0.278 0.033 256.848); /* Naranja/Dorado suave */
    border-color: oklch(0.5 0.15 70);
    color: white;
}

.bg-validador {
    background-color: oklch(0.446 0.03 256.802); /* Verde esmeralda claro */
    border-color: oklch(0.5 0.1 160);
    color: white;
}

.bg-usuario {
    background-color: oklch(0.928 0.006 264.531); /* Azul muy claro */
    border-color: oklch(0.5 0.05 250);
    color: oklch(0.2 0.05 250);
}


@media (max-width: 768px) {

    .acciones {
        flex-direction: column;
        align-items: stretch;
        justify-content: flex-start;
        gap: 10px;
    }

    .acciones input,
    .acciones select {
        width: 100%;
    }

    .botones {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .acciones button {
        width: 100%;
    }
}

@media (max-width: 768px) {

    .paginacion {
        position: static;
        margin-top: 20px;
        justify-content: center;
        padding: 0;
    }

    .paginacion button,
    .paginacion .pagina {
        height: 36px;
        font-size: 14px;
    }
}

@media (max-width: 768px) {
    .volver {
        position: static;
        width: 100%;
        margin-top: 20px;
    }
}




        
    </style>
</head>
<body>

    <nav class="navbar">
    <div class="nav-left">
        <span class="logo">Gestión de Usuarios</span>
    </div>
</nav>

    
    <div class="container">

    <div class="acciones">
        <input 
        type="text" 
        id="buscarRut" 
        placeholder="Buscar por RUT"
        onkeyup="filtrarRut()"
    >

    <select id="filtrarRol" onchange="filtrarUsuarios()">
            <option value="">Todos los roles</option>
            <option value="validador">Validador</option>
            <option value="administrador">Administrador</option>
            <option value="usuario">Usuario</option>
        </select>
    
    <div class="botones">
    <button onclick="window.location.href='form_usuarios.php'">
        CREAR NUEVO USUARIO
    </button>

    <button onclick="window.location.href='eliminar_usuario.php'">
        ELIMINAR USUARIO
    </button>

    <button onclick="window.location.href='actualizar_usuario.php'">
        EDITAR USUARIO
    </button>
</div>
</div>
    <div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre y Apellido</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Código</th>
                <th>RUT</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['Nombre_y_Apellido']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['Correo_Electronico']); ?></td>
                    <td class="rol"><div id="usuario"><?php echo htmlspecialchars($usuario['Tipo_de_Usuario']); ?></div></td>
                    <td>Activo</td>
                    <td><?php echo htmlspecialchars($usuario['Codigo_Usuario']); ?></td>
                    <td class="rut"><?php echo htmlspecialchars($usuario['RUT']); ?></td>
                    
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<button class="volver" type="button"
                onclick="window.location.href='panel_administrador.php'"
                >
            VOLVER AL PANEL
        </button>
        <div class="paginacion">
    <button id="btnAnterior">Anterior</button>
    <div id="numerosPaginas"></div>
    <button id="btnSiguiente">Siguiente</button>
</div>
    </div>



   <script>
const filas = Array.from(document.querySelectorAll("tbody tr"));
const filasPorPagina = 4;

let paginaActual = 1;
let filasFiltradas = [...filas];

const btnAnterior = document.getElementById("btnAnterior");
const btnSiguiente = document.getElementById("btnSiguiente");
const contenedorPaginas = document.getElementById("numerosPaginas");

function mostrarPagina(pagina) {
    paginaActual = pagina;

    const inicio = (pagina - 1) * filasPorPagina;
    const fin = inicio + filasPorPagina;

    filas.forEach(fila => fila.style.display = "none");

    filasFiltradas.slice(inicio, fin).forEach(fila => {
        fila.style.display = "";
    });

    actualizarPaginacion();
}

function actualizarPaginacion() {
    const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPagina);
    contenedorPaginas.innerHTML = "";

    for (let i = 1; i <= totalPaginas; i++) {
        const span = document.createElement("span");
        span.textContent = i;
        span.classList.add("pagina");
        if (i === paginaActual) span.classList.add("activa");

        span.onclick = () => mostrarPagina(i);
        contenedorPaginas.appendChild(span);
    }

    btnAnterior.disabled = paginaActual === 1;
    btnSiguiente.disabled = paginaActual === totalPaginas || totalPaginas === 0;
}

btnAnterior.onclick = () => {
    if (paginaActual > 1) mostrarPagina(paginaActual - 1);
};

btnSiguiente.onclick = () => {
    const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPagina);
    if (paginaActual < totalPaginas) mostrarPagina(paginaActual + 1);
};

function filtrarUsuarios() {
    const inputRut = document.getElementById("buscarRut").value.toLowerCase();
    const selectRol = document.getElementById("filtrarRol").value.toLowerCase();

    filasFiltradas = filas.filter(fila => {
        const rut = fila.querySelector(".rut").textContent.toLowerCase();
        const rol = fila.querySelector(".rol").textContent.toLowerCase();

        const coincideRut = rut.includes(inputRut);
        const coincideRol = selectRol === "" || rol.includes(selectRol);

        return coincideRut && coincideRol;
    });

    mostrarPagina(1);
}

// vincular input y select
document.getElementById("buscarRut").addEventListener("keyup", filtrarUsuarios);
document.getElementById("filtrarRol").addEventListener("change", filtrarUsuarios);

// inicial
mostrarPagina(1);

function filtrarRut() {
    const input = document.getElementById("buscarRut");
    const filtro = input.value.toLowerCase();
    const filas = document.querySelectorAll("tbody tr");

    filas.forEach(fila => {
        const rut = fila.querySelector(".rut").textContent.toLowerCase();

        if (rut.includes(filtro)) {
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });
}

function aplicarColoresRoles() {
    // Buscamos todos los divs que tengan el ID usuario (o mejor usamos selector por clase si puedes)
    const etiquetasUsuario = document.querySelectorAll("td.rol div");
    
    etiquetasUsuario.forEach(div => {
        const rol = div.textContent.trim().toLowerCase();
        
        // Limpiamos clases previas para no duplicar
        div.classList.remove('bg-administrador', 'bg-validador', 'bg-usuario');
        
        // Asignamos la clase según el contenido
        if (rol === 'administrador') {
            div.classList.add('bg-administrador');
        } else if (rol === 'validador') {
            div.classList.add('bg-validador');
        } else if (rol === 'usuario') {
            div.classList.add('bg-usuario');
        }
    });
}

// Llamar la función al cargar la página
aplicarColoresRoles();

// IMPORTANTE: En tu función mostrarPagina(pagina), 
// debes llamar a aplicarColoresRoles() al final para que los colores se mantengan al cambiar de página.
</script>



    
</body>
</html>