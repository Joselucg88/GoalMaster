<?php
session_start();

// Verifica si el usuario está logueado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Redirige a la página de dashboard del usuario
    if ($_SESSION['rol'] == 'promotor') {
        header("Location: /goalmaster/HTML/promotor/dashboard.php");
        exit;
    } elseif ($_SESSION['rol'] == 'manager') {
        header("Location: /goalmaster/HTML/manager/dashboard.php");
        exit;
    } elseif ($_SESSION['rol'] == 'admin') {
        header("Location: /goalmaster/HTML/admin/dashboard.php");
        exit;
    }
} else {
    // Si el usuario no está logueado, redirige al login
    header("Location: /goalmaster/HTML/login.html");
    exit;
}
?>

