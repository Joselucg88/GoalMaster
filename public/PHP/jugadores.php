<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

$usuario_rol = $_SESSION['rol'];
$usuario_id = $_SESSION['usuario_id'];

if (!isset($_GET['equipo_id'])) {
    echo "No se ha seleccionado un equipo.";
    exit;
}

$equipo_id = $_GET['equipo_id'];

// Verificar si el usuario es el manager del equipo o tiene permisos como promotor
$query_check_manager = "SELECT manager_id FROM equipos WHERE equipo_id = ?";
$stmt_check_manager = $conexion->prepare($query_check_manager);
$stmt_check_manager->bind_param("i", $equipo_id);
$stmt_check_manager->execute();
$result_check_manager = $stmt_check_manager->get_result();
$equipo_data = $result_check_manager->fetch_assoc();

if ($equipo_data['manager_id'] != $usuario_id && $usuario_rol != 'admin' && $usuario_rol != 'promotor') {
    echo "No tienes permisos para ver este equipo.";
    exit;
}

// Obtener los jugadores del equipo
$query_jugadores = "SELECT j.jugador_id, j.jugador_nombre FROM jugadores j WHERE j.equipo_id = ?";
$stmt_jugadores = $conexion->prepare($query_jugadores);
$stmt_jugadores->bind_param("i", $equipo_id);
$stmt_jugadores->execute();
$result_jugadores = $stmt_jugadores->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugadores del Equipo</title>
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
        <h2>Jugadores del Equipo</h2>
    </div>

    <div class="jugadores-container">
        <ul>
            <?php while ($jugador = $result_jugadores->fetch_assoc()): ?>
                <li>
                    <?php echo htmlspecialchars($jugador['jugador_nombre']); ?>
                    <?php if ($usuario_rol == 'manager'): ?>
                        <form action="eliminar_jugador.php" method="POST" style="display:inline;">
                            <input type="hidden" name="jugador_id" value="<?php echo $jugador['jugador_id']; ?>">
                            <button type="submit" class="btn-delete">Eliminar Jugador</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>

        <?php if ($usuario_rol == 'manager'): ?>
            <div class="btn-container">
                <a href="añadir_jugador.php?equipo_id=<?php echo $equipo_id; ?>" class="btn-add">Añadir Jugador</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
