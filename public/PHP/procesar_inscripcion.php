<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

if ($_SESSION['rol'] != 'manager') {
    echo "No tienes permisos para acceder a esta página.";
    exit;
}

include('../conexion.php');

// Obtener los datos del formulario
$equipo_id = $_POST['equipo_id'];
$competicion_id = $_POST['competicion_id'];
$fecha_inscripcion = date('Y-m-d');

// Insertar la inscripción en la base de datos
$query = $conexion->prepare("INSERT INTO inscripciones (equipo_id, competicion_id, fecha_inscripcion) VALUES (?, ?, ?)");
$query->bind_param("iis", $equipo_id, $competicion_id, $fecha_inscripcion);

if ($query->execute()) {
    echo "<script>alert('Equipo inscrito con éxito'); window.location.href = 'equipos.php';</script>";
} else {
    echo "<script>alert('Error al inscribir el equipo'); window.location.href = 'equipos.php';</script>";
}

// Cerrar la conexión
$query->close();
$conexion->close();
?>
