<?php
// procesar_login.php

use App\Controladores\ValidarCredenciales;
require("../vendor/autoload.php");

// Iniciar o reanudar la sesión
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los valores del formulario
    $rol = $_POST["rol"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Lógica de autenticación
    $controlador = new ValidarCredenciales();
    $credencialesValidas = false;

    if ($rol === "docente") {
        $credencialesValidas = $controlador->validarCredencialesDocente($usuario, $contrasena);
    } elseif ($rol === "estudiante") {
        $credencialesValidas = $controlador->validarCredencialesEstudiante($usuario, $contrasena);
    }

    // Redireccionamiento basado en el resultado de autenticación
    if ($credencialesValidas) {
        if ($rol === "docente") {
            $_SESSION['rol'] = 'docente';
            $_SESSION['usuario'] = $usuario;
            header("Location: ../docente/menuDocente.php");
            exit();
        } elseif ($rol === "estudiante") {
            $_SESSION['rol'] = 'estudiante';
            $_SESSION['usuario'] = $usuario;
            header("Location: ../estudiante/menuEstudiante.php");
            exit();
        }
    } else {
        // Las credenciales no son válidas, redirigir nuevamente al formulario de inicio de sesión
        header("Location: ../index.php");
        exit();
    }
} else {
    // Si se intenta acceder al archivo sin datos del formulario, redirigir al formulario de inicio de sesión
    header("Location: ../index.php");
    exit();
}
?>
