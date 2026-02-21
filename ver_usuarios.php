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
  /* ===== RESET & BASE ===== */
* { 
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: ui-sans-serif, system-ui, sans-serif;
}

body {
    background-color: #f4f6f8;
    padding-top: 80px; /* Espacio para la navbar fija */
}

/* ===== NAVBAR ===== */
.navbar {
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 64px;
    background-color: oklch(0.278 0.033 256.848);
    display: flex;
    align-items: center;
    padding: 0 24px;
    z-index: 1000;
}
.navbar .logo { color: #fff; font-size: 1.25rem; font-weight: 500; }

/* ===== CONTAINER PRINCIPAL ===== */
.container {
    max-width: 1200px;
    margin: 0 auto 40px auto;
    background: white;
    padding: 24px;
    border: 2px solid oklch(0.707 0.022 261.325);
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ===== ACCIONES (ARRIBA) ===== */
.acciones {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    justify-content: space-between;
}

.filtros-busqueda {
    display: flex;
    gap: 10px;
}

.botones-grupo {
    display: flex;
    gap: 8px; /* Botones más juntos como pediste */
}

.acciones input, .acciones select {
    height: 40px;
    padding: 8px 12px;
    border: 2px solid oklch(0.707 0.022 261.325);
    outline: none;
}

.acciones button {
    background-color: rgb(30, 41, 59);
    color: white;
    border: none;
    padding: 0 15px;
    height: 40px;
    cursor: pointer;
    font-weight: 500;
    transition: opacity 0.2s;
}

.acciones button:hover { opacity: 0.9; }

/* ===== TABLA ===== */
.tabla-responsive {
    width: 100%;
    overflow-x: auto;
    min-height: 250px; /* Evita que colapse si está vacía */
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border: 1px solid #eee;
    text-align: left;
}

th { background-color: rgb(30, 41, 59); color: white; }

/* ===== ROLES (ETIQUETAS) ===== */
.rol-tag {
    display: inline-block;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
    border-radius: 4px;
}
.bg-administrador { background: #1e293b; color: white; }
.bg-validador { background: #334155; color: white; }
.bg-usuario { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

/* ===== FOOTER DEL CONTAINER (BOTONES ABAJO) ===== */
.container-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.volver {
    background: white;
    border: 2px solid oklch(0.707 0.022 261.325);
    padding: 10px 20px;
    cursor: pointer;
    font-weight: 500;
}

.paginacion { display: flex; gap: 5px; align-items: center; }
.paginacion button { padding: 8px 12px; cursor: pointer; background: white; border: 1px solid #ccc; }
.paginacion .pagina {
    padding: 8px 15px;
    border: 1px solid #ccc;
    cursor: pointer;
}
.paginacion .pagina.activa { background: rgb(30, 41, 59); color: white; border-color: rgb(30, 41, 59); }

/* ===== MEDIA QUERIES MEJORADOS ===== */
@media (max-width: 900px) {
    .acciones { flex-direction: column; align-items: stretch; }
    .filtros-busqueda, .botones-grupo { flex-direction: column; width: 100%; }
    .container-footer { flex-direction: column-reverse; gap: 20px; }
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