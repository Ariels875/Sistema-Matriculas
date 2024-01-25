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

// Inicializar variables
$resultados = array();
$mensajeExito = ''; // Agregado para evitar el error de "Undefined variable"
$mensajeError = ''; // Agregado para evitar el error de "Undefined variable"

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y obtener los datos del formulario
    if (isset($_POST['busqueda'])) {
        $busqueda = $_POST['busqueda'];
        $resultados = $docenteController->buscarDocente($busqueda);
    } elseif (
        isset($_POST["input1"]) && isset($_POST["input2"]) && isset($_POST["input3"])
        && isset($_POST["input4"]) && isset($_POST["input5"]) && isset($_POST["input6"])
        && isset($_POST["input7"]) && isset($_POST["input8"])
    ) {
        // Recoger los valores de los inputs en variables PHP
        $input1 = $_POST["input1"];
        $input2 = $_POST["input2"];
        $input3 = $_POST["input3"];
        $input4 = $_POST["input4"];
        $input5 = $_POST["input5"];
        $input6 = $_POST["input6"];
        $input7 = $_POST["input7"];
        $input8 = $_POST["input8"];

        // Crear instancia del controlador y almacenar los datos
        $controlEstudiante = new DocenteController();
        $controlEstudiante->store([
            "cedula" => $input1,
            "primer_nombre" => $input2,
            "primer_apellido" => $input3,
            "fecha_nacimiento" => $input4,
            "telefono_celular" => $input5,
            "correo" => $input6,
            "direccion_domicilio" => $input7,
            "passwordd" => $input8
        ]);

        $mensajeExito = "Datos enviados correctamente.";
    } else {
        $mensajeError = "Por favor, complete todos los campos del formulario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar o Eliminar Docente</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
</head>
<body>
    <h1>Buscar Docente</h1>

    <!-- Formulario para buscar docentes -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="busqueda">Buscar por Cédula o Nombre:</label>
        <input type="text" id="busqueda" name="busqueda" required>
        <button type="submit">Buscar Docente</button>
    </form>

    <?php if (!empty($resultados)) : ?>
        <!-- Mostrar resultados en una tabla -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cédula</th>
                    <th>Primer Nombre</th>
                    <th>Primer Apellido</th>
                    <th>Fecha Nacimiento</th>
                    <th>Teléfono Celular</th>
                    <th>Correo</th>
                    <th>Dirección Domicilio</th>
                    <!-- Agregar más columnas según sea necesario -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $docente) : ?>
                    <tr>
                    <td><?php echo isset($docente['idDocente']) ? $docente['idDocente'] : ''; ?></td>
                    <td><?php echo isset($docente['cedula']) ? $docente['cedula'] : ''; ?></td>
                    <td><?php echo isset($docente['primer_nombre']) ? $docente['primer_nombre'] : ''; ?></td>
                    <td><?php echo isset($docente['primer_apellido']) ? $docente['primer_apellido'] : ''; ?></td>
                    <td><?php echo isset($docente['fecha_nacimiento']) ? $docente['fecha_nacimiento'] : ''; ?></td>
                    <td><?php echo isset($docente['telefono_celular']) ? $docente['telefono_celular'] : ''; ?></td>
                    <td><?php echo isset($docente['correo']) ? $docente['correo'] : ''; ?></td>
                    <td><?php echo isset($docente['direccion_domicilio']) ? $docente['direccion_domicilio'] : ''; ?></td>
                    <!-- Agregar más columnas según sea necesario -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br></br>



    <h1>Gestión de Docentes</h1>

<!-- Formulario para agregar docente -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h2>Agregar Docente</h2>
    <!-- Agregar campos del formulario según sea necesario -->
    <label for="input1">Cédula:</label>
    <input type="text" id="input1" name="input1" required><br><br>

    <label for="input2">Primer Nombre:</label>
    <input type="text" id="input2" name="input2" required><br><br>
    <label for="input3">Apellido:</label>
    <input type="text" name="input3" id="input3" required><br><br>

    <label for="input4">Fecha de Nacimiento:</label>
    <input type="date" name="input4" id="input4" required><br><br>

    <label for="input5">Numero celular:</label>
    <input type="text" name="input5" id="input5" required><br><br>

    <label for="input6">Correo:</label>
    <input type="text" name="input6" id="input6" required><br><br>

    <label for="input7">Direccion:</label>
    <input type="text" name="input7" id="input7" required><br><br>

    <label for="input8">Contraseña:</label>
    <input type="password" name="input8" id="input8" required><br><br>

    <!-- Agregar más campos según sea necesario -->

    <button type="submit" name="agregarDocente">Agregar Docente</button>
</form>

<!-- Formulario para eliminar docente -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h2>Eliminar Docente</h2>
    <label for="cedula_eliminar">Cédula del Docente a Eliminar:</label>
    <input type="text" id="cedula_eliminar" name="cedula_eliminar" required>

    <button type="submit" name="eliminarDocente">Eliminar Docente</button>
</form>

<?php if (!empty($listaDocentes)) : ?>
    <!-- Mostrar la lista de docentes en una tabla -->
    <h2>Listado de Docentes</h2>
    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Primer Nombre</th>
                <th>Primer Apellido</th>
                <th>Fecha Nacimiento</th>
                <th>Teléfono Celular</th>
                <th>Correo</th>
                <th>Dirección Domicilio</th>
                <!-- Agregar más columnas según sea necesario -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaDocentes as $docente) : ?>
                <tr>
                    <td><?php echo $docente['cedula']; ?></td>
                    <td><?php echo $docente['primer_nombre']; ?></td>
                    <td><?php echo $docente['primer_apellido']; ?></td>
                    <td><?php echo $docente['fecha_nacimiento']; ?></td>
                    <td><?php echo $docente['telefono_celular']; ?></td>
                    <td><?php echo $docente['correo']; ?></td>
                    <td><?php echo $docente['direccion_domicilio']; ?></td>
                    <!-- Agregar más columnas según sea necesario -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Mostrar mensajes de éxito o error -->
<?php if ($mensajeExito !== '') : ?>
    <p style="color: green;"><?php echo $mensajeExito; ?></p>
<?php endif; ?>

<?php if ($mensajeError !== '') : ?>
    <p style="color: red;"><?php echo $mensajeError; ?></p>
<?php endif; ?>

<br></br>
<button onclick="location.href='menuDocente.php'">Volver al Menú Docente</button>
</body>
</html>

