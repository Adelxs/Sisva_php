<?php
$host = "localhost";
$usuario = "root";
$contrasena = ""; 
$base_datos = "dbsisvaqr";

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

/* ================= CREAR TABLAS ================= */

$tablas = [

/* ----- BASE ----- */
"CREATE TABLE IF NOT EXISTS Categoria (
    Categoria VARCHAR(15) NOT NULL,
    PRIMARY KEY (Categoria)
) ENGINE=InnoDB",

"CREATE TABLE IF NOT EXISTS Tipo_de_Usuario (
    Tipo_de_Usuario VARCHAR(15) NOT NULL,
    PRIMARY KEY (Tipo_de_Usuario)
) ENGINE=InnoDB",

/* ----- USUARIOS ----- */
"CREATE TABLE IF NOT EXISTS Usuarios (
    Codigo_Usuario VARCHAR(10) NOT NULL,
    Contrasena VARCHAR(255) NOT NULL,
    RUT VARCHAR(10) NOT NULL,
    Correo_Electronico VARCHAR(100) NOT NULL,
    Nombre_y_Apellido VARCHAR(100) NOT NULL,
    Tipo_de_Usuario VARCHAR(15) NOT NULL,
    PRIMARY KEY (Codigo_Usuario),
    FOREIGN KEY (Tipo_de_Usuario)
        REFERENCES Tipo_de_Usuario(Tipo_de_Usuario)
) ENGINE=InnoDB",

/* ----- REGISTRO ACCESO ----- */
"CREATE TABLE IF NOT EXISTS Registro_Acceso (
    ID_Registro INT AUTO_INCREMENT NOT NULL,
    Codigo_Usuario VARCHAR(10) NOT NULL,
    Dato_Desconocido_1 VARCHAR(10),
    Dato_Desconocido_2 VARCHAR(100),
    PRIMARY KEY (ID_Registro),
    FOREIGN KEY (Codigo_Usuario)
        REFERENCES Usuarios(Codigo_Usuario)
) ENGINE=InnoDB",

/* ----- HISTORIALES ----- */
"CREATE TABLE IF NOT EXISTS Historial_de_Acciones (
    ID_Accion INT AUTO_INCREMENT NOT NULL,
    Codigo_Usuario VARCHAR(10) NOT NULL,
    Accion VARCHAR(30),
    Hora_Accion DATETIME,
    PRIMARY KEY (ID_Accion),
    FOREIGN KEY (Codigo_Usuario)
        REFERENCES Usuarios(Codigo_Usuario)
) ENGINE=InnoDB",

"CREATE TABLE IF NOT EXISTS Historial_de_Reportes (
    ID_Reporte INT AUTO_INCREMENT NOT NULL,
    Codigo_Usuario VARCHAR(10) NOT NULL,
    RUT VARCHAR(10),
    Nombre_y_Apellido VARCHAR(100),
    Categoria VARCHAR(15) NOT NULL,
    Detalles_del_Incidente VARCHAR(1000),
    Fecha DATETIME,
    Anexos VARCHAR(1000),
    Encargado_de_Resolucion VARCHAR(100),
    Estado_del_Incidente VARCHAR(100),
    Respuesta_al_Incidente VARCHAR(1000),
    PRIMARY KEY (ID_Reporte),
    FOREIGN KEY (Codigo_Usuario)
        REFERENCES Usuarios(Codigo_Usuario),
    FOREIGN KEY (Categoria)
        REFERENCES Categoria(Categoria)
) ENGINE=InnoDB",

"CREATE TABLE IF NOT EXISTS Historial_de_Acceso (
    ID_Historial INT AUTO_INCREMENT NOT NULL,
    Codigo_Usuario VARCHAR(10) NOT NULL,
    ID_Registro INT NOT NULL,
    RUT VARCHAR(10),
    Nombre_y_Apellido VARCHAR(100),
    Codigo_QR VARCHAR(100),
    Hora_y_Fecha_de_Salida DATETIME,
    Hora_y_Fecha_de_Ingreso DATETIME,
    PRIMARY KEY (ID_Historial),
    FOREIGN KEY (Codigo_Usuario)
        REFERENCES Usuarios(Codigo_Usuario),
    FOREIGN KEY (ID_Registro)
        REFERENCES Registro_Acceso(ID_Registro)
) ENGINE=InnoDB",

/* ----- REPORTES ----- */
"CREATE TABLE IF NOT EXISTS Lista_de_Reportes (
    ID_Reporte INT AUTO_INCREMENT PRIMARY KEY,
    Codigo_Usuario VARCHAR(10) NOT NULL,
    Titulo VARCHAR(100) NOT NULL,
    Categoria VARCHAR(50),
    Detalles TEXT,
    Fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    Estado VARCHAR(20) DEFAULT 'Abierto'
) ENGINE=InnoDB",

"CREATE TABLE IF NOT EXISTS Reporte_Imagenes (
    ID_Imagen INT AUTO_INCREMENT PRIMARY KEY,
    ID_Reporte INT NOT NULL,
    Ruta_Imagen VARCHAR(255) NOT NULL,
    Fecha_Subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Reporte)
        REFERENCES Lista_de_Reportes(ID_Reporte)
        ON DELETE CASCADE
) ENGINE=InnoDB",

/* ----- ENCARGADOS ----- */
"CREATE TABLE IF NOT EXISTS encargados (
    ID_Encargado INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    RUT VARCHAR(12) NOT NULL,
    Correo VARCHAR(100),
    UNIQUE (RUT)
) ENGINE=InnoDB",

"CREATE TABLE IF NOT EXISTS Reporte_Encargado (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ID_Reporte INT NOT NULL,
    ID_Encargado INT NOT NULL,
    FOREIGN KEY (ID_Reporte)
        REFERENCES Lista_de_Reportes(ID_Reporte)
        ON DELETE CASCADE,
    FOREIGN KEY (ID_Encargado)
        REFERENCES encargados(ID_Encargado)
) ENGINE=InnoDB",

"CREATE TABLE Recuperacion_Password (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Codigo_Usuario VARCHAR(10) NOT NULL,
    token VARCHAR(100) NOT NULL,
    expira DATETIME NOT NULL,
    usado BOOLEAN DEFAULT 0,
    FOREIGN KEY (Codigo_Usuario) REFERENCES Usuarios(Codigo_Usuario)
);"


];

/* ----- EJECUCIÓN ----- */
foreach ($tablas as $sql) {
    if (!$conexion->query($sql)) {
        die("Error creando tablas: " . $conexion->error);
    }
}

echo "Tablas creadas correctamente.<br>";

/* ================= INSERTS (SIN CAMBIOS) ================= */

$inserts = [
    "INSERT INTO Tipo_de_Usuario (Tipo_de_Usuario) VALUES ('Administrador')",
    "INSERT INTO Tipo_de_Usuario (Tipo_de_Usuario) VALUES ('Usuario')",
    "INSERT INTO Tipo_de_Usuario (Tipo_de_Usuario) VALUES ('Validador')",
    "INSERT INTO Categoria (Categoria) VALUES ('Hardware')",
    "INSERT INTO Usuarios (Codigo_Usuario, Contrasena, RUT, Nombre_y_Apellido, Tipo_de_Usuario) VALUES ('U001', 'clave123', '12345678-9', 'Juan Perez', 'Administrador')",
    "INSERT INTO Registro_Acceso (Codigo_Usuario, Dato_Desconocido_1, Dato_Desconocido_2) VALUES ('U001', 'DatoA', 'Informacion B')",
    "INSERT INTO Historial_de_Acciones (Codigo_Usuario, Accion, Hora_Accion) VALUES ('U001', 'Login Exitoso', NOW())",
    "INSERT INTO Historial_de_Reportes (Codigo_Usuario, RUT, Nombre_y_Apellido, Categoria, Detalles_del_Incidente, Fecha, Anexos, Encargado_de_Resolucion, Estado_del_Incidente, Respuesta_al_Incidente) VALUES ('U001', '12345678-9', 'Juan Perez', 'Hardware', 'El monitor no enciende.', NOW(), 'foto_monitor.jpg', 'Soporte TI', 'Abierto', 'Reporte recibido, se asignará técnico.')",
    "INSERT INTO Historial_de_Acceso (Codigo_Usuario, ID_Registro, RUT, Nombre_y_Apellido, Codigo_QR, Hora_y_Fecha_de_Salida, Hora_y_Fecha_de_Ingreso) VALUES ('U001', 1, '12345678-9', 'Juan Perez', 'QR_DATA_EXAMPLE_001', NULL, NOW())"
];

foreach ($inserts as $i => $query) {
    if ($conexion->query($query)) {
        echo "Insert " . ($i + 1) . " ejecutado correctamente.<br>";
    } else {
        echo "Error en insert " . ($i + 1) . ": " . $conexion->error . "<br>";
    }
}

echo "Proceso finalizado correctamente.";
?>

