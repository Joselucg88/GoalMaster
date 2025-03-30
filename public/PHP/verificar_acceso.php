<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de conexión a la base de datos
include_once('../PHP/conexion.php');

// Verificar si los datos del formulario se han enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Verificar si los campos están vacíos
    if (empty($email) || empty($password)) {
        echo "Por favor, ingrese todos los campos.";
        exit;
    }

    // Consultar el usuario en la base de datos por correo electrónico
    $query = $conexion->prepare("SELECT usuario_id, email, usuario_nombre, password_hash, rol FROM usuarios WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $usuario = $result->fetch_assoc();

    

    // Verificar si el usuario existe y si la contraseña es correcta
    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        // Iniciar la sesión y almacenar los datos del usuario
        $_SESSION['loggedin'] = true;
        $_SESSION['usuario_id'] = $usuario['usuario_id'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['usuario_nombre'] = $usuario['usuario_nombre']; // Añadir el nombre del usuario a la sesión
        $_SESSION['rol'] = $usuario['rol'];

        // Redirigir según el rol
        if ($usuario['rol'] == 'promotor') {
            header("Location: dashboard.php");
        } elseif ($usuario['rol'] == 'manager') {
            header("Location: dashboard.php");
        } elseif ($usuario['rol'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            echo "Rol no reconocido.";
            exit;
        }
    } else {
        echo "<script>alert('Las credenciales no son válidas'); window.location.href = '../HTML/login.html';</script>";
        exit();
    }

    // Cerrar la consulta y la conexión
    $query->close();
    $conexion->close();
}
?>
