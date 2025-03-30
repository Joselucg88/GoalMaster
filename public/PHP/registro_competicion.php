<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

$usuario_rol = $_SESSION['rol'];
$usuario_id = $_SESSION['usuario_id'];

// Procesar la creación de la competición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $competicion_nombre = $_POST['competicion_nombre'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Insertar la nueva competición
    $query_insert = "INSERT INTO competiciones (competicion_nombre, descripcion, fecha_inicio, fecha_fin, promotor_id) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conexion->prepare($query_insert);
    $stmt_insert->bind_param("ssssi", $competicion_nombre, $descripcion, $fecha_inicio, $fecha_fin, $usuario_id);
    
    if ($stmt_insert->execute()) {
        header("Location: ligas.php");  // Redirigir a la página de ligas después de crear
        exit;
    } else {
        echo "Error al crear la competición.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Competición</title>
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
        <h2>CREAR COMPETICIÓN</h2>
    </div>

    <div class="form-container">
        <form method="POST" action="registro_competicion.php">
            <label for="competicion_nombre">Nombre de la Competición:</label>
            <input type="text" id="competicion_nombre" name="competicion_nombre" required>

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required>

            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>

            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>

            <button type="submit">Crear Competición</button>
        </form>
    </div>
</body>
</html>
