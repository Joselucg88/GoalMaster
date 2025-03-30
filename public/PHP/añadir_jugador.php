<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../HTML/login.html");
    exit;
}

include('conexion.php');

$equipo_id = $_GET['equipo_id'];  // Obtener el ID del equipo desde la URL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del jugador desde el formulario
    $jugador_nombre = $_POST['jugador_nombre'];
    $edad = $_POST['edad'];
    $posicion = $_POST['posicion'];

    // Insertar el nuevo jugador
    $query_insert = "INSERT INTO jugadores (jugador_nombre, edad, posicion, equipo_id) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conexion->prepare($query_insert);
    $stmt_insert->bind_param("sssi", $jugador_nombre, $edad, $posicion, $equipo_id);
    
    if ($stmt_insert->execute()) {
        // Después de insertar, redirigir de nuevo a los detalles del equipo
        header("Location: equipos_detalles.php?equipo_id=" . $equipo_id);
        exit;
    } else {
        echo "Error al inscribir el jugador.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscribir Jugador</title>
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
        <h2>Inscribir un Jugador</h2>
    </div>

    <div class="form-container">
        <form method="POST" action="añadir_jugador.php?equipo_id=<?php echo $equipo_id; ?>">
            <label for="jugador_nombre">Nombre del Jugador:</label>
            <input type="text" id="jugador_nombre" name="jugador_nombre" required>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required>

            <label for="posicion">Posición:</label>
            <input type="text" id="posicion" name="posicion" required>

            <button type="submit">Inscribir Jugador</button>
        </form>
    </div>
</body>
</html>
