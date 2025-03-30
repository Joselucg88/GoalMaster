<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

$usuario_rol = $_SESSION['rol'];
$usuario_id = $_SESSION['usuario_id'];

if (!isset($_GET['jugador_id'])) {
    echo "No se ha seleccionado un jugador.";
    exit;
}

$jugador_id = $_GET['jugador_id'];

// Obtener los detalles del jugador para la edición
$query_jugador = "SELECT j.jugador_nombre, j.edad, j.posicion, j.equipo_id 
                  FROM jugadores j 
                  WHERE j.jugador_id = ?";
$stmt_jugador = $conexion->prepare($query_jugador);
$stmt_jugador->bind_param("i", $jugador_id);
$stmt_jugador->execute();
$result_jugador = $stmt_jugador->get_result();

if ($result_jugador->num_rows == 0) {
    echo "Jugador no encontrado.";
    exit;
}

$jugador_data = $result_jugador->fetch_assoc();
$equipo_id = $jugador_data['equipo_id'];

// Verificar si el jugador pertenece al equipo del manager
$query_check_equipo = "SELECT manager_id FROM equipos WHERE equipo_id = ?";
$stmt_check_equipo = $conexion->prepare($query_check_equipo);
$stmt_check_equipo->bind_param("i", $equipo_id);
$stmt_check_equipo->execute();
$result_check_equipo = $stmt_check_equipo->get_result();
$equipo_data = $result_check_equipo->fetch_assoc();

if ($equipo_data['manager_id'] != $usuario_id) {
    echo "No tienes permiso para editar este jugador.";
    exit;
}

// Si el formulario de editar se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si el botón de eliminar se ha presionado
    if (isset($_POST['eliminar'])) {
        // Eliminar jugador
        $query_delete_jugador = "DELETE FROM jugadores WHERE jugador_id = ?";
        $stmt_delete_jugador = $conexion->prepare($query_delete_jugador);
        $stmt_delete_jugador->bind_param("i", $jugador_id);
        if ($stmt_delete_jugador->execute()) {
            header("Location: jugadores.php?equipo_id=" . $equipo_id);
            exit;
        } else {
            echo "Error al eliminar el jugador.";
        }
    }
    // Si el formulario de actualización se ha enviado
    else {
        // Actualizar los detalles del jugador
        $jugador_nombre = $_POST['jugador_nombre'];
        $edad = $_POST['edad'];
        $posicion = $_POST['posicion'];

        $query_update_jugador = "UPDATE jugadores 
                                 SET jugador_nombre = ?, edad = ?, posicion = ? 
                                 WHERE jugador_id = ?";
        $stmt_update_jugador = $conexion->prepare($query_update_jugador);
        $stmt_update_jugador->bind_param("sisi", $jugador_nombre, $edad, $posicion, $jugador_id);

        if ($stmt_update_jugador->execute()) {
            header("Location: jugadores.php?equipo_id=" . $equipo_id);
            exit;
        } else {
            echo "Error al actualizar el jugador.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Jugador</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="dashboard.php">Inicio</a></li>
                <li><a href="ligas.php">Ligas</a></li>
                <li><a href="equipos.php">Equipos</a></li>
                <li><a href="cerrar_sesion.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-header">
        <h2>Editar Jugador: <?php echo htmlspecialchars($jugador_data['jugador_nombre']); ?></h2>
    </div>

    <form action="editar_jugador.php?jugador_id=<?php echo $jugador_id; ?>" method="POST">
        <label for="jugador_nombre">Nombre del Jugador:</label>
        <input type="text" name="jugador_nombre" value="<?php echo htmlspecialchars($jugador_data['jugador_nombre']); ?>" required><br>

        <label for="edad">Edad:</label>
        <input type="number" name="edad" value="<?php echo htmlspecialchars($jugador_data['edad']); ?>" required><br>

        <label for="posicion">Posición:</label>
        <input type="text" name="posicion" value="<?php echo htmlspecialchars($jugador_data['posicion']); ?>" required><br>

        <button type="submit">Actualizar Jugador</button>
    </form>

    <form action="editar_jugador.php?jugador_id=<?php echo $jugador_id; ?>" method="POST" style="display:inline;">
        <input type="hidden" name="eliminar" value="1">
        <button type="submit" class="btn-delete">Eliminar Jugador</button>
    </form>

</body>
</html>
