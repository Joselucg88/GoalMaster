<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

// Obtener el ID de la competición desde la URL
$competicion_id = $_GET['competicion_id'];

// Depurar el competicion_id para verificar que es correcto
var_dump($competicion_id);  // Esto debería mostrar el ID de la competición

// Obtener los detalles de la competición
$query_competicion = "SELECT competicion_nombre, descripcion, fecha_inicio, fecha_fin 
                      FROM competiciones 
                      WHERE competicion_id = ?";
$stmt_competicion = $conexion->prepare($query_competicion);
$stmt_competicion->bind_param("i", $competicion_id);
$stmt_competicion->execute();
$result_competicion = $stmt_competicion->get_result();
$competicion = $result_competicion->fetch_assoc();

// Obtener los equipos inscritos en la competición
$query_equipos = "SELECT e.equipo_id, e.equipo_nombre 
                  FROM equipos e 
                  WHERE e.competicion_id = ?";
$stmt_equipos = $conexion->prepare($query_equipos);
$stmt_equipos->bind_param("i", $competicion_id);
$stmt_equipos->execute();
$result_equipos = $stmt_equipos->get_result();

// Depurar el número de filas devueltas
var_dump($result_equipos->num_rows);  // Esto te ayudará a ver cuántos equipos se encuentran
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Competición</title>
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

    <div class="competicion-detalles">
        <h1>Competición: <?php echo htmlspecialchars($competicion['competicion_nombre']); ?></h1>
        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($competicion['descripcion']); ?></p>
        <p><strong>Fechas:</strong> <?php echo htmlspecialchars($competicion['fecha_inicio']); ?> - <?php echo htmlspecialchars($competicion['fecha_fin']); ?></p>

        <h2>Equipos en esta Competición</h2>
        <?php if ($result_equipos->num_rows > 0): ?>
            <ul>
                <?php while ($equipo = $result_equipos->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($equipo['equipo_nombre']); ?></strong>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No hay equipos inscritos en esta competición.</p>
        <?php endif; ?>
    </div>
</body>
</html>
