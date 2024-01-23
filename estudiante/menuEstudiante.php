<?php
session_start();

// Verificar si la sesión está iniciada y si el rol es estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'estudiante') {
    // Si no hay sesión o el rol no es estudiante, redirigir al formulario de inicio de sesión
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Estudiante</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
</head>
<body>
    <h1>Menú Principal</h1>

    <div class="menu-buttons">
        <button onclick="location.href='matricularse.php'">Matricularse</button>
        <button onclick="location.href='carrera_periodo.php'">Darse de baja</button>
        <button onclick="location.href='asignaturas_aulas.php'">Horario</button>
        <button onclick="location.href='pagos.php'">Pagos</button>
    </div>

    <br></br>

    <form action="../login/logout.php" method="post">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>
</body>
</html>
