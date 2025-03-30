<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

$usuario_rol = $_SESSION['rol'];
$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['jugador_id'])) {
    $jugador_id = $_POST['jugador_id'];

    // Verificar si el jugador existe en la base de datos
    $query_check_jugador = "SELECT equipo_id FROM jugadores WHERE jugador_id = ?";
    $stmt_check_jugador = $conexion->prepare($query_check_jugador);
    $stmt_check_jugador->bind_param("i", $jugador_id);
    $stmt_check_jugador->execute();
    $result_check_jugador = $stmt_check_jugador->get_result();
    
    if ($result_check_jugador->num_rows > 0) {
        $jugador_data = $result_check_jugador->fetch_assoc();

        // Verificar si el jugador pertenece al equipo del manager
        $query_check_equipo = "SELECT manager_id FROM equipos WHERE equipo_id = ?";
        $stmt_check_equipo = $conexion->prepare($query_check_equipo);
        $stmt_check_equipo->bind_param("i", $jugador_data['equipo_id']);
        $stmt_check_equipo->execute();
        $result_check_equipo = $stmt_check_equipo->get_result();
        $equipo_data = $result_check_equipo->fetch_assoc();

        // Comprobar que el manager es el propietario del equipo
        if ($equipo_data['manager_id'] != $usuario_id) {
            echo "No tienes permiso para eliminar este jugador.";
            exit;
        }

        // Eliminar jugador de la base de datos
        $query_delete_jugador = "DELETE FROM jugadores WHERE jugador_id = ?";
        $stmt_delete_jugador = $conexion->prepare($query_delete_jugador);
        $stmt_delete_jugador->bind_param("i", $jugador_id);

        if ($stmt_delete_jugador->execute()) {
            // Redirigir a la página de jugadores del equipo después de la eliminación
            header("Location: jugadores.php?equipo_id=" . $jugador_data['equipo_id']);
            exit;
        } else {
            echo "Error al eliminar el jugador.";
        }
    } else {
        echo "Jugador no encontrado.";
        exit;
    }
} else {
    // Mensaje de depuración si el formulario no fue enviado correctamente
    echo "No se ha enviado el formulario correctamente. Asegúrate de que el jugador_id esté presente.";
    exit;
}
?>
