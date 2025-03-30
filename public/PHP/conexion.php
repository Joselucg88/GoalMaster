<?php
// Datos de conexión a la base de datos
$host = "localhost";
$user = "root"; // Usuario de la base de datos
$password = ""; // Contraseña de la base de datos
$database = "goalmaster_db"; // Base de datos que se está utilizando

// Conexión a la base de datos
$conexion = mysqli_connect($host, $user, $password, $database);

// Verificar la conexión
if (empty($conexion)) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}