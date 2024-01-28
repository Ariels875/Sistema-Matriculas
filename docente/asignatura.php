<?php
// asignatura.php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
    // Destruir la sesión
    session_destroy();

    // Redirigir al usuario
    header("Location: ../index.php");
    exit();
}

use App\Controladores\AsignaturasController; // Cambiar el nombre del controlador
require("../vendor/autoload.php");
$asignaturaController = new AsignaturasController(); // Cambiar el nombre del controlador
$mensajeExito = '';
$mensajeError = '';
$resultados = array();

// Obtén la acción del formulario
$action = isset($_POST['action']) ? $_POST['action'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignaturas y periodos</title> <!-- Cambiar el título -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="stylesheet" href="../css/styletable.css" />
</head>
<body>
    <!-- Botones de acción -->
    <button id="indexBtn">Mostrar todas las Asignaturas</button> <!-- Cambiar el texto del botón -->
    <button id="showBtn">Buscar Datos de una Asignatura</button>
    <button id="actualizarBtn">Editar una Asignatura</button>
    <button id="deleteBtn">Eliminar una Asignatura</button>
    <button id="createBtn">Crear una Asignatura</button>
    <button onclick="location.href='aulas.php'">Modificar Aulas</button>
    <button onclick="location.href='menuDocente.php'">Volver al Menú Docente</button>
    <form action="#" method="post">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>

    <?php
    switch ($action) {
        case 'show':
            // Lógica para mostrar los datos de una asignatura
            if (isset($_POST['busqueda'])) {
                $busqueda = $_POST['busqueda'];
                $resultados = $asignaturaController->buscarAsignatura($busqueda); // Cambiar el nombre de la función
                // Cambiar el nombre de la función de resultados
                $asignaturaController->ResultadosBuscarAsignatura($resultados);
            }
            break;

        case 'update':
            // Lógica para actualizar los datos de la asignatura
            if (isset($_POST['enviado'])) {
                // Formulario para que el usuario especifique el ID de la asignatura
                // Si se envió el formulario con el ID de la asignatura
                $asignaturaId = isset($_POST['asignaturaId']) ? $_POST['asignaturaId'] : '';
                $info = $asignaturaController->showAsignatura($asignaturaId, $nivel_idNivel, $nivel_carrera_idCarrera); // Cambiar el nombre de la función

                if ($info) { // Asegurarse de que se obtuvo información de la asignatura
                    ?>
                    <!-- Formulario de actualización -->
                    <form method="post" action="asignatura.php">
                        <!-- ... campos para actualizar -->
                        <label for="nombre_asignatura">Nuevo Nombre de la Asignatura:</label>
                        <input type="text" name="nombre_asignatura" value="<?php echo htmlspecialchars($info["nombre_asignatura"]); ?>" required><br><br>

                        <label for="creditos">Créditos:</label>
                        <input type="number" name="creditos" value="<?php echo htmlspecialchars($info["creditos"]); ?>" required><br><br>

                        <!-- Puedes seguir añadiendo más campos según tu tabla -->

                        <input type="hidden" name="asignatura_Id" value="<?php echo htmlspecialchars($info["idAsignatura"]); ?>">
                        <input type="submit" name="actualizar" value="Actualizar Datos">
                    </form>
                    <?php

                    if (isset($_POST['actualizar'])) {
                        $nombreAsignatura = isset($_POST['nombre_asignatura']) ? $_POST['nombre_asignatura'] : '';
                        $creditos = isset($_POST['creditos']) ? $_POST['creditos'] : '';
                        $asignaturaId = isset($_POST['asignatura_Id']) ? $_POST['asignatura_Id'] : '';

                        $data = array(
                            'nombre_asignatura' => $nombreAsignatura,
                            'creditos' => $creditos
                            // Puedes añadir más campos aquí según tu tabla
                        );

                        $asignaturaController->updateAsignatura($idAsignatura, $nivel_idNivel, $nivel_carrera_idCarrera, $data); // Cambiar el nombre de la función
                        $mensajeExito = "Datos de la asignatura actualizados correctamente.";
                    }
                } else {
                    // Manejar el caso en que no se encontró la asignatura con el ID especificado
                    echo "La asignatura con el ID especificado no fue encontrada.";
                }
            }
            break;

        case 'destroy':
            // Lógica para eliminar una asignatura
            if (isset($_POST["nombre_asignatura"])) {
                $nombreAsignatura = isset($_POST['nombre_asignatura']) ? $_POST['nombre_asignatura'] : '';
                $asignaturaController->destroyAsignatura($idAsignatura, $nivel_idNivel, $nivel_carrera_idCarrera); // Cambiar el nombre de la función

                $mensajeExito = "La asignatura ha sido eliminada";
            } else {
                $mensajeError = "Por favor, complete todos los campos del formulario.";
            }
            break;

        case 'store':
            // Lógica para almacenar los datos de la asignatura
            if (isset($_POST["nombre_asignatura"])) {
                $data = array(
                    'nombre_asignatura' => $_POST['nombre_asignatura'],
                    'creditos' => $_POST['creditos']
                    // Puedes añadir más campos aquí según tu tabla
                );
                $asignaturaController->storeAsignatura($data); // Cambiar el nombre de la función
                $mensajeExito = "Datos enviados correctamente.";
            } else {
                $mensajeError = "Por favor, complete todos los campos del formulario.";
            }
            break;

        case 'index':
            // Lógica para mostrar todas las asignaturas
            $asignaturaController->indexAsignatura(); // Cambiar el nombre de la función
            break;

        default:
            // Lógica para mostrar la página principal o algún mensaje de error
            break;
    }
    ?>

    <div>
        <?php
        if (!empty($mensajeExito)) {
            echo '<div class="alert success">' . $mensajeExito . '</div>';
        } elseif (!empty($mensajeError)) {
            echo '<div class="alert error">' . $mensajeError . '</div>';
        }
        ?>
    </div>

    <!-- Formularios ocultos -->
    <div id="showForm" style="display:none;">
        <form method="post" action="asignatura.php">
            <!-- ... campos para mostrar datos -->
            <input type="hidden" name="action" value="show">
            <label for="busqueda">Buscar por Nombre o Créditos de la asignatura:</label>
            <input type="text" name="busqueda">
            <input type="submit" value="Mostrar Datos">
        </form>
    </div>

    <div id="deleteForm" style="display:none;">
        <form method="post" action="asignatura.php">
            <!-- ... campos para eliminar -->
            <input type="hidden" name="action" value="destroy">
            <label for="nombre_asignatura">Nombre de la asignatura a eliminar:</label>
            <input type="text" name="nombre_asignatura" required><br><br>
            <input type="submit" value="Eliminar asignatura">
        </form>
    </div>

    <div id="createForm" style="display:none;">
        <form method="post" action="asignatura.php">
            <!-- ... campos para crear -->
            <label for="nombre_asignatura">Nombre de la asignatura a crear:</label>
            <input type="text" name="nombre_asignatura" required><br><br>
            <label for="creditos">Créditos:</label>
            <input type="number" name="creditos" required><br><br>
            <input type="hidden" name="action" value="store">
            <input type="submit" value="Crear Asignatura">
        </form>
    </div>

    <div id="actualizarForm" style="display:none;">
        <form method="post" action="asignatura.php">
            <input type="hidden" name="action" value="update">
            <label for="asignaturaId">Ingrese el ID de la asignatura a actualizar:</label>
            <input type="text" name="asignaturaId" required>
            <input type="hidden" name="enviado" value="true">
            <input type="submit" value="Enviar">
        </form>
    </div>

    <script>
        $(document).ready(function () {
            // Mostrar formulario de Mostrar Datos de una Asignatura
            $('#showBtn').click(function () {
                hideAllForms();
                $('#showForm').show();
            });

            // Mostrar formulario de Eliminar Asignatura
            $('#deleteBtn').click(function () {
                hideAllForms();
                $('#deleteForm').show();
            });

            // Mostrar formulario de Crear Asignatura
            $('#createBtn').click(function () {
                hideAllForms();
                $('#createForm').show();
            });

            // Mostrar formulario de Actualizar Asignatura
            $('#actualizarBtn').click(function () {
                hideAllForms();
                $('#actualizarForm').show();
            });

            function hideAllForms() {
                // Ocultar todos los formularios
                $('#showForm, #editForm, #deleteForm, #createForm, #actualizarForm').hide();
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#indexBtn').click(function () {
                // Cambiar el valor del campo de acción a 'index'
                $('input[name="action"]').val('index');
                // Enviar el formulario
                $('form').submit();
            });
        });
    </script>

</body>
</html>
