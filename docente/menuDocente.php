<?php
session_start();

// Verificar si la sesión está iniciada y si el rol es docente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'docente') {
    // Si no hay sesión o el rol no es docente, redirigir al formulario de inicio de sesión
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
// Manejar la lógica para verificar la contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_password"])) {
    $password = $_POST["confirm_password"];

    // Validar la contraseña (en este caso, la contraseña es "superusuario")
    if ($password === "superusuario") {
        header("Location: agregar_docente.php");
        exit();
    } else {
        $error_message = "Contraseña incorrecta. Inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Docente</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
</head>
<body>
    <h1>Menú Docente</h1>

    <div class="menu-buttons">
        <button onclick="location.href='modificar_estudiante.php'">Modificar Estudiante</button>
        <button onclick="location.href='carrera.php'">Modificar Carrera y Periodo Académico</button>
        <button onclick="location.href='asignatura.php'">Modificar Asignaturas</button>
        <button onclick="location.href='modificar_datos_docente.php'">Modificar Mis Datos</button>
        <button onclick="location.href='gestionPagos.php'">Gestionar Pagos</button>


    </div>
    <br>        <!-- botón para agregar/insertar docente -->
    <form action="#" method="post">
        <button type="submit">Agregar/Eliminar Docente</button><br>
            <?php if (isset($error_message)) { echo "<p>$error_message</p>"; } ?>
        <label for="confirm_password">Para usar esta opcion usted debe ingresar la contraseña del Administrador:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

    </form>

    <br></br>

    <form action="#" method="post">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>
</body>
</html>
