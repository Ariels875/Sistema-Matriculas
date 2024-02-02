<?php
session_start();
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
use App\Controladores\EstudianteController;
require("../vendor/autoload.php");
$estudianteController = new EstudianteController();

// Inicializar variables
$resultados = array();
$mensajeExito = '';
$mensajeError = '';

//$info = $estudianteController->show($user);
// Procesar el formulario cuando se envía
// Manejar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = isset($_POST['form_type']) ? $_POST['form_type'] : '';

    switch ($form_type) {
        case 'buscarEstudiante':
            // Lógica para el formulario de búsqueda
            if (isset($_POST['busqueda'])) {
                $busqueda = $_POST['busqueda'];
                $resultados = $estudianteController->buscarEstudiante($busqueda);
                // Haz algo con los resultados, como mostrarlos en la página.
            }
            break;

        case 'modificarEstudiante':
            // Lógica para el formulario de modificar estudiante
            if (isset($_POST["enviar"])) {
                // Obtener el valor del input
                $cedula = isset($_POST["cedula"]) ? $_POST["cedula"] : null;

                if ($cedula) {
                    // Llamar a la función show del controlador
                    $estudiante = $estudianteController->show($cedula);

                    // Mostrar los datos en el formulario de actualización
                    if ($estudiante) {
                        mostrarFormularioActualizacion($estudiante);
                    } else {
                        echo "No se encontraron datos para la cédula proporcionada.";
                    }
                } else {
                    echo "Por favor, proporcione una cédula.";
                }
            } elseif (isset($_POST["actualizar"])) {
                // Procesar el formulario de actualización
                $cedula = isset($_POST["cedula"]) ? $_POST["cedula"] : null;
                $data = [
                    "primer_nombre" => isset($_POST["primer_nombre"]) ? $_POST["primer_nombre"] : null,
                    "primer_apellido" => isset($_POST["primer_apellido"]) ? $_POST["primer_apellido"] : null,
                    "fecha_nacimiento" => isset($_POST["fecha_nacimiento"]) ? $_POST["fecha_nacimiento"] : null,
                    "telefono_celular" => isset($_POST["telefono_celular"]) ? $_POST["telefono_celular"] : null,
                    "correo" => isset($_POST["correo"]) ? $_POST["correo"] : null,
                    "direccion_domicilio" => isset($_POST["direccion_domicilio"]) ? $_POST["direccion_domicilio"] : null,
                    "passwordd" => isset($_POST["passwordd"]) ? $_POST["passwordd"] : null,
                    "idEstudiante" => isset($_POST["idEstudiante"]) ? $_POST["idEstudiante"] : null
                ];

                // Llamar a la función update del controlador
                if ($cedula && $data["idEstudiante"]) {
                    $estudianteController->update($cedula, $data);
                    echo "Datos actualizados correctamente.";
                } else {
                    echo "Error al procesar el formulario de actualización.";
                }
            }
            break;

        case 'eliminarEstudiante':
            if (isset($_POST['cedula_eliminar'])) {
                $cedula_eliminar = $_POST['cedula_eliminar'];
                $estudianteController->destroyEstudiante($cedula_eliminar);
                $mensajeExito = "Estudiante eliminado correctamente.";
            } else {
                $mensajeError = "Por favor, proporciona la cédula del estudiante que deseas eliminar.";
            }
            break;

        default:
            // Manejar cualquier otro caso xd
            break;
    }
} else {
    // Formulario inicial
    mostrarFormulario();
}

// Función para mostrar el formulario inicial
function mostrarFormulario() {
    echo '
        <html>
        <head>
            <title>Formulario de Estudiante</title>
            <link rel="stylesheet" href="../css/styleadmin.css" />
        </head>
        <body>
            <h1>Panel de Control Estudiante</h1>
            <form method="post" action="modificar_estudiante.php">
                
                <label for="cedula">Cédula del estudiante a modificar:</label>
                <input type="text" name="cedula" required>
                <input type="hidden" name="form_type" value="modificarEstudiante">
                <input type="submit" name="enviar" value="Modificar Datos">
            </form>
        </body>
        </html>
    ';
}

// Función para mostrar el formulario de actualización
function mostrarFormularioActualizacion($estudiante) {
    echo '
        <html>
        <head>
            <title>Formulario de Actualización</title>
            <link rel="stylesheet" href="../css/styleadmin.css" />
        </head>
        <body>
            <h1>Panel de Control Estudiante</h1>
            <form method="post" action="modificar_estudiante.php">
                <input type="hidden" name="idEstudiante" value="' . $estudiante["idEstudiante"] . '">
                <label for="cedula">Cedula:</label>
                <input type="text" name="cedula" value="' . $estudiante["cedula"] . '" required><br><br>

                <label for="cedula">Primer Nombre:</label>
                <input type="text" name="primer_nombre" value="' . $estudiante["primer_nombre"] . '" required><br><br>

                <label for="primer_apellido">Primer Apelllido:</label>
                <input type="text" name="primer_apellido" value="' . $estudiante["primer_apellido"] . '" required><br><br>

                <label for="correo">Correo:</label>
                <input type="text" name="correo" value="' . $estudiante["correo"] . '" required><br><br>

                <label for="direccion_domicilio">Direccion:</label>
                <input type="text" name="direccion_domicilio" value="' . $estudiante["direccion_domicilio"] . '" required><br><br>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" value="' . $estudiante["fecha_nacimiento"] . '" required><br><br>

                <label for="passwordd">Contraseña:</label>
                <input type="password" name="passwordd" value="' . $estudiante["passwordd"] . '" required><br><br>

                <label for="telefono_celular">Numero Celular:</label>
                <input type="text" name="telefono_celular" value="' . $estudiante["telefono_celular"] . '" required><br><br>
                <!-- Agregar otros campos del estudiante según tu estructura -->
                <input type="hidden" name="form_type" value="modificarEstudiante">
                <input type="submit" name="actualizar" value="Actualizar Datos">
            </form>
        </body>
        </html>
    ';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control Estudiantes</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="stylesheet" href="../css/styletable.css" />
    <!--<link rel="stylesheet" href="../css/styleforms.css" Esto aun no funciona:(  />-->
</head>
<body>

    <div id="content">
        <!-- Mostrar mensajes de éxito o error -->
        <?php
        if (!empty($mensajeExito)) {
            echo '<div class="alert success">' . $mensajeExito . '</div>';
        } elseif (!empty($mensajeError)) {
            echo '<div class="alert error">' . $mensajeError . '</div>';
        }
        ?>

        <!-- Formulario para eliminar estudiante -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="form_type" value="eliminarEstudiante">

            <label for="cedula_eliminar">Cédula del Estudiante a Eliminar:</label>
            <input type="text" name="cedula_eliminar" id="cedula_eliminar" required>
            <button type="submit">Eliminar Estudiante</button>
        </form>

        <!-- Formulario para buscar estudiante -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="form_type" value="buscarEstudiante">

            <label for="busqueda">Buscar Estudiante:</label>
            <input type="text" name="busqueda" id="busqueda" required>
            <button type="submit">Buscar</button>
        </form>
    </div>
 <!-- Muestra los resultados de la búsqueda -->
 <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $form_type === 'buscarEstudiante') {
        // Solo muestra los resultados si se ha enviado el formulario de búsqueda
        if (!empty($resultados)) {
            echo '<h2>Resultados de la búsqueda:</h2>';
            echo '<table>';
            echo '<tr><th>Cédula</th><th>Nombre</th><th>Apellido</th><th>Fecha de Nacimiento</th><th>Correo</th><th>Direccion</th></tr>';
            
            foreach ($resultados as $estudiante) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($estudiante['cedula']) . '</td>';
                echo '<td>' . htmlspecialchars($estudiante['primer_nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($estudiante['primer_apellido']) . '</td>';
                echo '<td>' . htmlspecialchars($estudiante['fecha_nacimiento']) . '</td>';
                echo '<td>' . htmlspecialchars($estudiante['correo']) . '</td>';
                echo '<td>' . htmlspecialchars($estudiante['direccion_domicilio']) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No se encontraron resultados.</p>';
        }
    }
    ?>
    <button onclick="location.href='modificar_estudiante.php'">Modificar otro estudiante</button>
    <button onclick="location.href='menuDocente.php'">Volver al Menú Docente</button><br><br>
    <form action="#" method="post">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>

</body>
</html>