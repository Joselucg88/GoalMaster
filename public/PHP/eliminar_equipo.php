<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

// Verificar si se ha recibido un equipo_id
if (isset($_POST['equipo_id'])) {
    $equipo_id = $_POST['equipo_id'];

    // Eliminar equipo
    $query = "DELETE FROM equipos WHERE equipo_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $equipo_id);
    if ($stmt->execute()) {
        header("Location: equipos.php");  // Redirigir a la página de equipos después de eliminar
        exit;
    } else {
        echo "Error al eliminar el equipo.";
    }
} else {
    echo "No se ha especificado el equipo a eliminar.";
}
?>
