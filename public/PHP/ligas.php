<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

// Obtener el rol del usuario
$usuario_rol = $_SESSION['rol'];

// Obtener las competiciones disponibles
$query_ligas = "SELECT competicion_id, competicion_nombre, descripcion, fecha_inicio, fecha_fin FROM competiciones";
$stmt_ligas = $conexion->prepare($query_ligas);
$stmt_ligas->execute();
$result_ligas = $stmt_ligas->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ligas</title>
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

    <div class="liga-list">
        <h2>COMPETICIONES</h2>
        <?php if ($result_ligas->num_rows > 0): ?>
            <ul>
                <?php while ($liga = $result_ligas->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($liga['competicion_nombre']); ?></strong>
                        <p><?php echo htmlspecialchars($liga['descripcion']); ?></p>
                        <p>Inicio: <?php echo htmlspecialchars($liga['fecha_inicio']); ?> | Fin: <?php echo htmlspecialchars($liga['fecha_fin']); ?></p>

                        <?php
                        // Obtener los equipos registrados en esta competición
                        $competicion_id = $liga['competicion_id'];
                        $query_equipos = "SELECT e.equipo_nombre 
                                          FROM equipos e 
                                          WHERE e.competicion_id = ?";
                        $stmt_equipos = $conexion->prepare($query_equipos);
                        $stmt_equipos->bind_param("i", $competicion_id);
                        $stmt_equipos->execute();
                        $result_equipos = $stmt_equipos->get_result();

                        if ($result_equipos->num_rows > 0): ?>
                            <strong>Equipos Registrados:</strong>
                            <ul>
                                <?php while ($equipo = $result_equipos->fetch_assoc()): ?>
                                    <li><?php echo htmlspecialchars($equipo['equipo_nombre']); ?></li>
                                <?php endwhile; ?> <br><br>
                            </ul>
                        <?php else: ?>
                            <p>No hay equipos registrados en esta competición.</p>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No hay competiciones disponibles.</p>
        <?php endif; ?>
    </div>
</body>
</html>
