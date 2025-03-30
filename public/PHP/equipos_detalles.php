<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

$equipo_id = $_GET['equipo_id'];  // Obtener el ID del equipo desde la URL

// Obtener los detalles del equipo
$query_equipo = "SELECT e.equipo_nombre, c.competicion_nombre
                 FROM equipos e
                 LEFT JOIN competiciones c ON e.competicion_id = c.competicion_id
                 WHERE e.equipo_id = ?";
$stmt_equipo = $conexion->prepare($query_equipo);
$stmt_equipo->bind_param("i", $equipo_id);
$stmt_equipo->execute();
$result_equipo = $stmt_equipo->get_result();
$equipo = $result_equipo->fetch_assoc();

// Obtener los jugadores del equipo
$query_jugadores = "SELECT j.jugador_id, j.jugador_nombre, j.edad, j.posicion
                    FROM jugadores j 
                    WHERE j.equipo_id = ?";
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
    <title>Detalles del Equipo</title>
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
        <h2>Detalles del Equipo: <?php echo htmlspecialchars($equipo['equipo_nombre']); ?></h2>
        <p>Competición: <?php echo htmlspecialchars($equipo['competicion_nombre']); ?></p>
    </div>

    <div class="jugadores-list">
        <h3>JUGADORES:</h3>
        <?php if ($result_jugadores->num_rows > 0): ?>
            <ul>
                <?php while ($jugador = $result_jugadores->fetch_assoc()): ?>
                    <li>
                    <strong><?php echo htmlspecialchars($jugador['jugador_nombre']); ?></strong>
                    - Edad: <?php echo htmlspecialchars($jugador['edad']); ?> años
                    - Posición: <?php echo htmlspecialchars($jugador['posicion']); ?>
                        <?php if ($_SESSION['rol'] == 'manager'): ?>
                            <a href="editar_jugador.php?jugador_id=<?php echo $jugador['jugador_id']; ?>" class="btn-edit">Editar Jugador</a>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No hay jugadores en este equipo.</p>
        <?php endif; ?>
    </div>

    <?php if ($_SESSION['rol'] == 'manager'): ?>
        <div class="btn-container">
            <a href="añadir_jugador.php?equipo_id=<?php echo $equipo_id; ?>" class="btn-add">Añadir Jugador</a>
        </div>
    <?php endif; ?>
</body>
</html>
