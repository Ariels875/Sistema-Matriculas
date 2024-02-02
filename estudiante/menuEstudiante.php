<?php
session_start();
// Verificar si la sesión está iniciada y si el rol es estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'estudiante') {
    // Si no hay sesión o el rol no es estudiante, redirigir al formulario de inicio de sesión
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
    // Destruir la sesión
    session_destroy();
    
    // Redirigir al usuario
    header("Location: ../index.php");
    exit();
}
require("../vendor/autoload.php");
use App\Controladores\EstudianteController;
$estudianteController = new EstudianteController;

$cedulaUsuario = $_SESSION['usuario'];
$infoUser = $estudianteController->show($cedulaUsuario);
$nombreUser = $infoUser['primer_nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Estudiante</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="shortcut icon" href="https://icons8.com/icon/88192/conflict">
</head>
<body>
    <h1>Menú Principal</h1>
    <h2>Hola <?= $nombreUser; ?> espero que estes teniendo un bonito día.</h2>

    <div class="menu-buttons">
        
        <button onclick="location.href='matricularse.php'">Matricularse</button>
        <button onclick="location.href='darseBaja.php'">Darse de baja</button>
        <button onclick="location.href='horarios.php'">Horario</button>
        <button onclick="location.href='pagos.php'">Pagos</button>
    </div>

    <br></br>

    <form action="#" method="post">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>
</body>
</html>
