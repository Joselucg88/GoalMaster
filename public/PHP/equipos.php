<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

$usuario_rol = $_SESSION['rol'];
$usuario_id = $_SESSION['usuario_id'];

// Si el rol es manager, obtener los equipos donde sea manager
if ($usuario_rol === 'manager') {
    $query_equipos = "SELECT e.equipo_id, e.equipo_nombre, c.competicion_nombre
                      FROM equipos e
                      LEFT JOIN competiciones c ON e.competicion_id = c.competicion_id
                      WHERE e.manager_id = ?";
    $stmt_equipos = $conexion->prepare($query_equipos);
    $stmt_equipos->bind_param("i", $usuario_id);
} 

// Si el rol es promotor, obtener los equipos de las competiciones gestionadas por el promotor
elseif ($usuario_rol === 'promotor') {
    $query_equipos = "SELECT e.equipo_id, e.equipo_nombre, c.competicion_nombre
                      FROM equipos e
                      LEFT JOIN competiciones c ON e.competicion_id = c.competicion_id
                      WHERE c.promotor_id = ?";
    $stmt_equipos = $conexion->prepare($query_equipos);
    $stmt_equipos->bind_param("i", $usuario_id);
}

$stmt_equipos->execute();
$result_equipos = $stmt_equipos->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos</title>
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
        <h2>MIS EQUIPOS</h2>
    </div>

    <div class="equipos-list">
        <?php if ($result_equipos->num_rows > 0): ?>
            <ul>
                <?php while ($equipo = $result_equipos->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($equipo['equipo_nombre']); ?></strong>
                        <p>Competición: <?php echo htmlspecialchars($equipo['competicion_nombre']); ?></p>
                        <a href="equipos_detalles.php?equipo_id=<?php echo $equipo['equipo_id']; ?>">Ver Jugadores</a>
                        <br><br><br>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No tienes equipos registrados aún.</p>
        <?php endif; ?> 

        <?php if ($usuario_rol === 'manager'): ?>
            <div class="btn-container">
                <a href="inscribir_equipo.php" class="btn-add">Crear un nuevo equipo</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
