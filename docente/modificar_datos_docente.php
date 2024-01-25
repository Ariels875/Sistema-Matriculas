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
// Incluir el controlador y crear una instancia
use App\Controladores\DocenteController;
require("../vendor/autoload.php");

$docenteController = new DocenteController();

// Obtener la cedula a partir del user
$user = $_SESSION['usuario'];

// Obtener la información del docente actual retornando la info del docente
$docenteinfo = $docenteController->show($user);

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y obtener los datos del formulario
    $nuevosDatos = array(
        'primer_nombre' => $_POST['primer_nombre'],
        'primer_apellido' => $_POST['primer_apellido'],
        'telefono_celular' => $_POST['telefono_celular'],
        'correo' => $_POST['correo'],
        'direccion_domicilio' => $_POST['direccion_domicilio'],
        'passwordd'=>$_POST['passwordd'],
        'fecha_nacimiento' => $docenteinfo['fecha_nacimiento'],
        'cedula' => $user
    );

    // Actualizar los datos del docente
    $docenteController->update($user, $nuevosDatos);

    // Redirigir a la página de éxito o a donde sea necesario
    header("Location: modificar_datos_docente.php?exito=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Datos del Docente</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
</head>
<body>
    <h1>Modificar Datos del Docente</h1>

    <!-- Formulario para modificar los datos -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="primer_nombre">Nombre:</label>
        <input type="text" id="primer_nombre" name="primer_nombre" value="<?php echo $docenteinfo['primer_nombre']; ?>" required><br><br>

        <label for="primer_apellido">Apellido:</label>
        <input type="text" id="primer_apellido" name="primer_apellido" value="<?php echo $docenteinfo['primer_apellido']; ?>" required><br><br>

        <label for="fecha_nacimiento">fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $docenteinfo['fecha_nacimiento']; ?>" required><br><br>

        <label for="telefono_celular">Teléfono:</label>
        <input type="text" id="telefono_celular" name="telefono_celular" value="<?php echo $docenteinfo['telefono_celular']; ?>" required><br><br>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" value="<?php echo $docenteinfo['correo']; ?>" required><br><br>

        <label for="direccion_domicilio">Dirección Domicilio:</label>
        <input type="text" id="direccion_domicilio" name="direccion_domicilio" value="<?php echo $docenteinfo['direccion_domicilio']; ?>" required><br><br>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="passwordd" value="<?php echo $docenteinfo['passwordd']; ?>" required><br><br>

        <button type="submit">Guardar Cambios</button><br>
    </form>

    <br></br>

    <button onclick="location.href='menuDocente.php'">Volver al Menú Docente</button>
        <form action="#" method="post">
            <button type="submit" name="logout">Cerrar sesión</button>
        </form>
</body>
</html>
