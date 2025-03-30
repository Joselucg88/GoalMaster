<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

// Obtener datos del usuario
$usuario_nombre = $_SESSION['usuario_nombre'];
$usuario_rol = $_SESSION['rol'];
$usuario_id = $_SESSION['usuario_id'];

// Si el usuario es promotor, obtener las competiciones que ha creado
if ($usuario_rol == 'promotor') {
    $query_ligas = "SELECT competicion_id, competicion_nombre, descripcion, fecha_inicio, fecha_fin 
                    FROM competiciones 
                    WHERE promotor_id = ?";
    $stmt_ligas = $conexion->prepare($query_ligas);
    $stmt_ligas->bind_param("i", $usuario_id);
    $stmt_ligas->execute();
    $result_ligas = $stmt_ligas->get_result();
}

// Si el usuario es manager, obtener los equipos que ha creado y sus competiciones
if ($usuario_rol == 'manager') {
    $query_equipos = "SELECT e.equipo_id, e.equipo_nombre, e.competicion_id 
                      FROM equipos e 
                      WHERE e.manager_id = ?";
    $stmt_equipos = $conexion->prepare($query_equipos);
    $stmt_equipos->bind_param("i", $usuario_id);
    $stmt_equipos->execute();
    $result_equipos = $stmt_equipos->get_result();

    // Obtener la competición de cada equipo
    $equipos_info = [];
    while ($equipo = $result_equipos->fetch_assoc()) {
        $competicion = null;
        if ($equipo['competicion_id']) {
            $competicion_id = $equipo['competicion_id'];
            $query_competicion = "SELECT competicion_nombre FROM competiciones WHERE competicion_id = ?";
            $stmt_competicion = $conexion->prepare($query_competicion);
            $stmt_competicion->bind_param("i", $competicion_id);
            $stmt_competicion->execute();
            $result_competicion = $stmt_competicion->get_result();
            $competicion = $result_competicion->fetch_assoc();
        }
        $equipos_info[] = [
            'equipo_nombre' => $equipo['equipo_nombre'],
            'competicion' => $competicion ? $competicion['competicion_nombre'] : "No inscrito en una liga"
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

    <div class="welcome-message">
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?>!</h1>
    </div>

    <?php if ($usuario_rol == 'promotor'): ?>
        <div class="promotor-dashboard">
            <h2>TUS COMPETICIONES</h2>
            <?php if ($result_ligas->num_rows > 0): ?>
                <ul>
                    <?php while ($liga = $result_ligas->fetch_assoc()): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($liga['competicion_nombre']); ?></strong>
                            <p><?php echo htmlspecialchars($liga['descripcion']); ?></p>
                            <p>Inicio: <?php echo htmlspecialchars($liga['fecha_inicio']); ?> | Fin: <?php echo htmlspecialchars($liga['fecha_fin']); ?></p><br><br><br>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No has creado ninguna competición aún.</p>
            <?php endif; ?>

            <div class="btn-container">
                <a href="registro_competicion.php" class="btn-add">Crear una nueva competición</a>
            </div>
        </div>
    <?php elseif ($usuario_rol == 'manager'): ?>
        <div class="manager-dashboard">
            <h2>INFORMACIÓN DE TUS EQUIPOS</h2>
            <?php if (count($equipos_info) > 0): ?>
                <ul>
                    <?php foreach ($equipos_info as $equipo): ?>
                        <li>
                            <strong>Equipo:</strong> <?php echo htmlspecialchars($equipo['equipo_nombre']); ?>
                            <br>
                            <strong>Competición (Liga):</strong> <?php echo htmlspecialchars($equipo['competicion']); ?>
                            <br><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No tienes equipos asignados.</p>
            <?php endif; ?>

            <div class="btn-container">
                <a href="equipos.php" class="btn-add">Crear un nuevo equipo</a>
            </div>
        </div>

    <?php else: ?>
        <div class="general-dashboard">
            <h2>Información</h2>
            <p>Equipo: <?php echo $_SESSION['equipo_nombre'] ?? 'No asignado'; ?></p>
            <p>Competición (Liga): <?php echo $_SESSION['competicion_nombre'] ?? 'No inscrito en una liga'; ?></p>
        </div>
    <?php endif; ?>
</body>

</html>