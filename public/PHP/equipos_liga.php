<?php
// Iniciar la sesión
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

// Verificar si se recibió el ID de la competición
if (!isset($_GET['competicion_id']) || empty($_GET['competicion_id'])) {
    die("ID de competición no proporcionado.");
}

$competicion_id = $_GET['competicion_id'];

// Incluir la conexión a la base de datos
include('conexion.php');

// Consultar los equipos de la competición
$query = $conexion->prepare("SELECT e.equipo_id, e.equipo_nombre 
                             FROM equipos e
                             INNER JOIN inscripciones i ON e.equipo_id = i.equipo_id
                             WHERE i.competicion_id = ?");
$query->bind_param("i", $competicion_id);
$query->execute();
$resultado = $query->get_result();

// Obtener el nombre de la competición
$query_nombre = $conexion->prepare("SELECT competicion_nombre FROM competiciones WHERE competicion_id = ?");
$query_nombre->bind_param("i", $competicion_id);
$query_nombre->execute();
$resultado_nombre = $query_nombre->get_result();
$competicion = $resultado_nombre->fetch_assoc();

if (!$competicion) {
    die("Competición no encontrada.");
}

$competicion_nombre = $competicion['competicion_nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos en <?php echo $competicion_nombre; ?></title>
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
    <div class="equipos-container">
        <h2>Equipos en la competición: <?php echo $competicion_nombre; ?></h2>
        <ul>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($equipo = $resultado->fetch_assoc()): ?>
                    <li><?php echo $equipo['equipo_nombre']; ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No hay equipos inscritos en esta competición.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$query->close();
$query_nombre->close();
$conexion->close();
?>
