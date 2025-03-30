<?php
session_start();
include('conexion.php');

// Verificar que el usuario sea promotor
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'promotor') {
    echo "No tienes permisos para realizar esta acción.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $competicion_id = $_POST['competicion_id'];

    // Verificar que la competición pertenece al promotor
    $promotor_id = $_SESSION['usuario_id'];
    $query_check = "SELECT * FROM competiciones WHERE competicion_id = ? AND promotor_id = ?";
    $stmt_check = $conexion->prepare($query_check);
    $stmt_check->bind_param("ii", $competicion_id, $promotor_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Eliminar la competición
        $query_delete = "DELETE FROM competiciones WHERE competicion_id = ?";
        $stmt_delete = $conexion->prepare($query_delete);
        $stmt_delete->bind_param("i", $competicion_id);
        $stmt_delete->execute();

        if ($stmt_delete->affected_rows > 0) {
            echo "Competición eliminada correctamente.";
        } else {
            echo "Error al eliminar la competición.";
        }
    } else {
        echo "No tienes permiso para eliminar esta competición.";
    }
}
header("Location: ligas.php");
exit;
?>
