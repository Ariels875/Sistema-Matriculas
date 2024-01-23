<?php
session_start();

// Verificar si la sesión está iniciada y si el rol es docente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'docente') {
    // Si no hay sesión o el rol no es docente, redirigir al formulario de inicio de sesión
    header("Location: ../index.php");
    exit();
}

// Incluir el controlador y crear una instancia
use App\Controladores\DocenteController;
require("../vendor/autoload.php");

$docenteController = new DocenteController();

// Obtener el id a partir del user

// $user = $_SESSION['usuario'];
$docente = $docenteController->show($usuario);

// Obtener la información del docente actual
// $docenteId = $_SESSION['docente_id'];
// $docente = $docenteController->show($docenteId);

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y obtener los datos del formulario
    $nuevosDatos = array(
        'primer_nombre' => $_POST['primer_nombre'],
        'primer_apellido' => $_POST['primer_apellido'],
        'telefono_celular' => $_POST['telefono_celular'],
        'correo' => $_POST['correo'],
        'direccion_domicilio' => $_POST['direccion_domicilio'],
        'passwordd'=>$_POST['passwordd']
    );

    // Actualizar los datos del docente
    $docenteController->update($docenteId, $nuevosDatos);

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
        <label for="primer_nombre">Primer Nombre:</label>
        <input type="text" id="primer_nombre" name="primer_nombre" value="<?php echo $docente['primer_nombre']; ?>" required>

        <label for="primer_apellido">Primer Apellido:</label>
        <input type="text" id="primer_apellido" name="primer_apellido" value="<?php echo $docente['primer_apellido']; ?>" required>

        <label for="telefono_celular">Teléfono Celular:</label>
        <input type="text" id="telefono_celular" name="telefono_celular" value="<?php echo $docente['telefono_celular']; ?>" required>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" value="<?php echo $docente['correo']; ?>" required>

        <label for="direccion_domicilio">Dirección Domicilio:</label>
        <input type="text" id="direccion_domicilio" name="direccion_domicilio" value="<?php echo $docente['direccion_domicilio']; ?>" required>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="passwordd" value="<?php echo $docente['passwordd']; ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>

    <br></br>

    <button onclick="location.href='menuDocente.php'">Volver al Menú Docente</button>
</body>
</html>
