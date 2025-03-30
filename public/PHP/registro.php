<?php
// Incluir el archivo de conexión a la base de datos
include_once('../PHP/conexion.php');

// Verificar si los datos han sido enviados a través del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $usuario_nombre = $_POST['usuario_nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Verificar si los campos están vacíos
    if (empty($usuario_nombre) || empty($email) || empty($password) || empty($rol)) {
        echo "Por favor, rellene todos los campos.";
        exit;
    }

    // Comprobar si el correo electrónico ya existe en la base de datos
    $query = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo "Este correo electrónico ya está registrado.";
        exit;
    }

    // Encriptar la contraseña antes de almacenarla
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario en la base de datos
    $query = $conexion->prepare("INSERT INTO usuarios (usuario_nombre, email, password_hash, rol) VALUES (?, ?, ?, ?)");
    $query->bind_param("ssss", $usuario_nombre, $email, $password_hash, $rol);

    if ($query->execute()) {
        echo "Registro exitoso.";
        // Redirigir a la página de login después del registro
        header("Location: ../HTML/login.html");
        exit;
    } else {
        echo "Error al registrar el usuario.";
    }

    // Cerrar la consulta y la conexión
    $query->close();
    $conexion->close();
}
?>
