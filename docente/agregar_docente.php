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

// Incluir el controlador y crear una instancia, pilas cristian 
use App\Controladores\DocenteController;
require("../vendor/autoload.php");
$docenteController = new DocenteController();

// Inicializar variables
$resultados = array();
$mensajeExito = '';
$mensajeError = '';

// Procesar el formulario cuando se envía, esto fue dificil de hacer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = isset($_POST['form_type']) ? $_POST['form_type'] : '';

    switch ($form_type) {
        case 'buscarDocente':
            if (isset($_POST['busqueda'])) {
                $busqueda = $_POST['busqueda'];
                $resultados = $docenteController->buscarDocente($busqueda);
            }
            break;

        case 'agregarDocente':
            if (
                isset($_POST["input1"]) && isset($_POST["input2"]) && isset($_POST["input3"])
                && isset($_POST["input4"]) && isset($_POST["input5"]) && isset($_POST["input6"])
                && isset($_POST["input7"]) && isset($_POST["input8"])
            ) {
                $input1 = $_POST["input1"];
                $input2 = $_POST["input2"];
                $input3 = $_POST["input3"];
                $input4 = $_POST["input4"];
                $input5 = $_POST["input5"];
                $input6 = $_POST["input6"];
                $input7 = $_POST["input7"];
                $input8 = $_POST["input8"];

                $docenteController->store([
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
            break;

            case 'eliminarDocente':
                if (isset($_POST['cedula_eliminar'])) {
                    $cedula_eliminar = $_POST['cedula_eliminar'];
                    $docenteController->destroy($cedula_eliminar);
                    $mensajeExito = "Docente eliminado correctamente.";
                } else {
                    $mensajeError = "Por favor, proporciona la cédula del docente que deseas eliminar.";
                }
            break;

        default:
            // Manejar cualquier otro caso xd
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control Docente</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="stylesheet" href="../css/styletable.css" />
    <!--<link rel="stylesheet" href="../css/styleforms.css" Esto aun no funciona:(  />-->
</head>
<body>

    <div id="content">
        <h1>Panel de Control Docente</h1>

        <!-- Mostrar mensajes de éxito o error -->
        <?php
        if (!empty($mensajeExito)) {
            echo '<div class="alert success">' . $mensajeExito . '</div>';
        } elseif (!empty($mensajeError)) {
            echo '<div class="alert error">' . $mensajeError . '</div>';
        }
        ?>

        <!-- Formulario para agregar docente -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="form_type" value="agregarDocente">

            <div class="form-column">
                <label for="input1">Cédula:</label>
                <input type="text" id="input1" name="input1" required><br><br>

                <label for="input2">Primer Nombre:</label>
                <input type="text" id="input2" name="input2" required><br><br>

                <label for="input3">Apellido:</label>
                <input type="text" name="input3" id="input3" required><br><br>

                <label for="input4">Fecha de Nacimiento:</label>
                <input type="date" name="input4" id="input4" required><br><br>

            </div>
            <div class="form-column">
                <label for="input5">Número celular:</label>
                <input type="text" name="input5" id="input5" required><br><br>

                <label for="input6">Correo:</label>
                <input type="text" name="input6" id="input6" required><br><br>

                <label for="input7">Dirección:</label>
                <input type="text" name="input7" id="input7" required><br><br>

                <label for="input8">Contraseña:</label>
                <input type="password" name="input8" id="input8" required><br><br>
            </div>
            <button type="submit">Agregar Docente</button>
        </form>

        <!-- Formulario para eliminar docente -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="form_type" value="eliminarDocente">

            <label for="cedula_eliminar">Cédula del Docente a Eliminar:</label>
            <input type="text" name="cedula_eliminar" id="cedula_eliminar" required>
            <button type="submit">Eliminar Docente</button>
        </form>

        <!-- Formulario para buscar docente -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="form_type" value="buscarDocente">

            <label for="busqueda">Buscar Docente:</label>
            <input type="text" name="busqueda" id="busqueda" required>
            <button type="submit">Buscar</button>
        </form>
    </div>
 <!-- Muestra los resultados de la búsqueda -->
 <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $form_type === 'buscarDocente') {
        // Solo muestra los resultados si se ha enviado el formulario de búsqueda
        if (!empty($resultados)) {
            echo '<h2>Resultados de la búsqueda:</h2>';
            echo '<table>';
            echo '<tr><th>Cédula</th><th>Nombre</th><th>Apellido</th><th>Fecha de Nacimiento</th><th>Correo</th><th>Direccion</th></tr>';
            
            foreach ($resultados as $docente) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($docente['cedula']) . '</td>';
                echo '<td>' . htmlspecialchars($docente['primer_nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($docente['primer_apellido']) . '</td>';
                echo '<td>' . htmlspecialchars($docente['fecha_nacimiento']) . '</td>';
                echo '<td>' . htmlspecialchars($docente['correo']) . '</td>';
                echo '<td>' . htmlspecialchars($docente['direccion_domicilio']) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No se encontraron resultados.</p>';
        }
    }
    ?>
        <button onclick="location.href='menuDocente.php'">Volver al Menú Docente</button>
        <form action="#" method="post">
            <button type="submit" name="logout">Cerrar sesión</button>
        </form>
</body>
</html>