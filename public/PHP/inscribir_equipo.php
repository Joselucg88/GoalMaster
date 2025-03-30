<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

$usuario_rol = $_SESSION['rol'];
$usuario_id = $_SESSION['usuario_id'];

// Obtener las competiciones disponibles
$query_competiciones = "SELECT competicion_id, competicion_nombre FROM competiciones";
$stmt_competiciones = $conexion->prepare($query_competiciones);
$stmt_competiciones->execute();
$result_competiciones = $stmt_competiciones->get_result();

// Procesar el formulario de creación de equipo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $equipo_nombre = $_POST['equipo_nombre'];
    $competicion_id = $_POST['competicion_id'];

    // Insertar el nuevo equipo
    $query_insert = "INSERT INTO equipos (equipo_nombre, manager_id, competicion_id) VALUES (?, ?, ?)";
    $stmt_insert = $conexion->prepare($query_insert);
    $stmt_insert->bind_param("sii", $equipo_nombre, $usuario_id, $competicion_id);
    if ($stmt_insert->execute()) {
        header("Location: equipos.php");  // Redirigir a la página de equipos después de la creación
        exit;
    } else {
        echo "Error al crear el equipo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Equipo</title>
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
        <h2>Crear Nuevo Equipo</h2>
    </div>

    <div class="form-container">
        <form method="POST" action="inscribir_equipo.php">
            <label for="equipo_nombre">Nombre del Equipo:</label>
            <input type="text" id="equipo_nombre" name="equipo_nombre" required>

            <label for="competicion_id">Seleccionar Competición:</label>
            <select name="competicion_id" id="competicion_id" required>
                <?php while ($competicion = $result_competiciones->fetch_assoc()): ?>
                    <option value="<?php echo $competicion['competicion_id']; ?>">
                        <?php echo htmlspecialchars($competicion['competicion_nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Crear Equipo</button>
        </form>
    </div>
</body>
</html>
