<?php
// carrera.php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
    // Destruir la sesión
    session_destroy();

    // Redirigir al usuario
    header("Location: ../index.php");
    exit();
}

use App\Controladores\CarreraController;
require("../vendor/autoload.php");
$carreraController = new CarreraController();
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
    <title>Carreras y periodos</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="stylesheet" href="../css/styletable.css" />
</head>
<body>
    <!-- Botones de acción -->
    <button id="indexBtn">Mostrar todas las Carreras</button>
    <button id="showBtn">Buscar Datos de una Carrera</button>
    <button id="actualizarBtn">Editar una Carrera</button>
    <button id="deleteBtn">Eliminar una Carrera</button>
    <button id="createBtn">Crear una Carrera</button>
    <button onclick="location.href='periodo.php'">Modificar Periodo Académico</button>
    <button onclick="location.href='menuDocente.php'">Volver al Menú Docente</button>
    <form action="#" method="post">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>

    <?php
    switch ($action) {
        case 'show':
            // Lógica para mostrar los datos de una carrera
            if (isset($_POST['busqueda'])) {
                $busqueda = $_POST['busqueda'];
                $resultados = $carreraController->buscarCarrera($busqueda);
                $carreraController->ResultadosBuscarCarrera($resultados);
            }
            break;

        case 'update':
            // Lógica para actualizar los datos de la carrera
            if (isset($_POST['enviado'])) {
                // Formulario para que el usuario especifique el ID de la carrera
                // Si se envió el formulario con el ID de la carrera
                $carreraId = isset($_POST['carreraId']) ? $_POST['carreraId'] : '';
                $info = $carreraController->showCarrera($carreraId);

                if ($info) { // Asegurarse de que se obtuvo información de la carrera
                    ?>
                    <!-- Formulario de actualización -->
                    <form method="post" action="carrera.php">
                        <!-- ... campos para actualizar -->
                        <label for="nombre_carrera">Nuevo Nombre de la Carrera:</label>
                        <input type="text" name="nombre_carrera" value="<?php echo htmlspecialchars($info["nombre_carrera"]); ?>" required><br><br>

                        <label for="facultad">Facultad en la que estará</label>
                        <select id="facultad" name="facultad">
                            <option value="FACULTAD DE EDUCACIÓN, CIENCIA Y TECNOLOGÍA">FACULTAD DE EDUCACIÓN, CIENCIA Y TECNOLOGÍA</option>
                            <option value="FACULTAD DE CIENCIAS ADMINISTRATIVAS Y ECONÓMICAS">FACULTAD DE CIENCIAS ADMINISTRATIVAS Y ECONÓMICAS</option>
                            <option value="FACULTAD DE CIENCIAS DE LA SALUD">FACULTAD DE CIENCIAS DE LA SALUD</option>
                            <option value="FACULTAD DE INGENIERÍA EN CIENCIAS APLICADAS">FACULTAD DE INGENIERÍA EN CIENCIAS APLICADAS</option>
                            <option value="FACULTAD DE INGENIERÍA EN CIENCIAS AGROPECUARIAS Y AMBIENTALES">FACULTAD DE INGENIERÍA EN CIENCIAS AGROPECUARIAS Y AMBIENTALES</option>
                            <!-- Asegúrate de seleccionar la facultad correcta en función de $info -->
                        </select><br><br>

                        <label for="modalidad">Modalidad en la que estará</label>
                        <select id="modalidad" name="modalidad">
                            <option value="PRESENCIAL">PRESENCIAL</option>
                            <option value="SEMIPRESENCIAL">SEMIPRESENCIAL</option>
                            <option value="EN LÍNEA">EN LÍNEA</option>
                            <!-- Asegúrate de seleccionar la modalidad correcta en función de $info -->
                        </select><br><br>

                        <input type="hidden" name="carrera_Id" value="<?php echo htmlspecialchars($info["idCarrera"]); ?>">
                        <input type="submit" name="actualizar" value="Actualizar Datos">
                    </form>
                    <?php

                    if (isset($_POST['actualizar'])) {
                        $nombreCarrera = isset($_POST['nombre_carrera']) ? $_POST['nombre_carrera'] : '';
                        $facultad = isset($_POST['facultad']) ? $_POST['facultad'] : '';
                        $modalidad = isset($_POST['modalidad']) ? $_POST['modalidad'] : '';
                        $carreraId = isset($_POST['carrera_Id']) ? $_POST['carrera_id'] : '';

                        $data = array(
                            'nombre_carrera' => $nombreCarrera,
                            'facultad' => $facultad,
                            'modalidad' => $modalidad
                            // ... (otras variables)
                        );

                        $carreraController->updateCarrera($carreraId, $data);
                        $mensajeExito = "Datos de la carrera actualizados correctamente.";
                        }
                } else {
                    // Manejar el caso en que no se encontró la carrera con el ID especificado
                    echo "La carrera con el ID especificado no fue encontrada.";
                }
            }
            break;


        case 'destroy':
            // Lógica para eliminar una carrera
            if (isset($_POST["nombre_carrera"])) {
                $nombreCarrera = isset($_POST['nombre_carrera']) ? $_POST['nombre_carrera'] : '';
                $carreraController->destroyCarrera($nombreCarrera);

                $mensajeExito = "La carrera ha sido eliminada";
            } else {
                $mensajeError = "Por favor, complete todos los campos del formulario.";
            }
            break;

        case 'store':
            // Lógica para almacenar los datos de la carrera
            if (isset($_POST["nombre_carrera"])) {
                $data = array(
                    'nombre_carrera' => $_POST['nombre_carrera'],
                    'facultad' => $_POST['facultad'],
                    'modalidad' => $_POST['modalidad']
                    // ... (otras variables)
                );
                $carreraController->storeCarrera($data);
                $mensajeExito = "Datos enviados correctamente.";
            } else {
                $mensajeError = "Por favor, complete todos los campos del formulario.";
            }
            break;

            case 'index':
                // Lógica para mostrar todas las carreras
                $carreraController->indexCarrera();

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
        <form method="post" action="carrera.php">
            <!-- ... campos para mostrar datos -->
            <input type="hidden" name="action" value="show">
            <label for="busqueda">Buscar por Nombre, Facultad o Modalidad de la carrera:</label>
            <input type="text" name="busqueda">
            <input type="submit" value="Mostrar Datos">
        </form>
    </div>

    <div id="deleteForm" style="display:none;">
        <form method="post" action="carrera.php">
            <!-- ... campos para eliminar -->
            <input type="hidden" name="action" value="destroy">
            <label for="nombre_carrera">Nombre de la carrera a eliminar:</label>
            <input type="text" name="nombre_carrera" required><br><br>
            <input type="submit" value="Eliminar carrera">
        </form>
    </div>

    <div id="createForm" style="display:none;">
        <form method="post" action="carrera.php">
            <!-- ... campos para crear -->
            <label for="nombre_carrera">Nombre de la carrera a crear:</label>
            <input type="text" name="nombre_carrera" required><br><br>
            <label for="facultad">Facultad en la que estará</label>
            <select id="facultad" name="facultad">
                <option value="FACULTAD DE EDUCACIÓN, CIENCIA Y TECNOLOGÍA">FACULTAD DE EDUCACIÓN, CIENCIA Y TECNOLOGÍA</option>
                <option value="FACULTAD DE CIENCIAS ADMINISTRATIVAS Y ECONÓMICAS">FACULTAD DE CIENCIAS ADMINISTRATIVAS Y ECONÓMICAS</option>
                <option value="FACULTAD DE CIENCIAS DE LA SALUD">FACULTAD DE CIENCIAS DE LA SALUD</option>
                <option value="FACULTAD DE INGENIERÍA EN CIENCIAS APLICADAS">FACULTAD DE INGENIERÍA EN CIENCIAS APLICADAS</option>
                <option value="FACULTAD DE INGENIERÍA EN CIENCIAS AGROPECUARIAS Y AMBIENTALES">FACULTAD DE INGENIERÍA EN CIENCIAS AGROPECUARIAS Y AMBIENTALES</option>
            </select><br><br>
            <label for="modalidad">Modalidad en la que estará</label>
            <select id="modalidad" name="modalidad">
                <option value="PRESENCIAL">PRESENCIAL</option>
                <option value="SEMIPRESENCIAL">SEMIPRESENCIAL</option>
                <option value="EN LÍNEA">EN LÍNEA</option>
            </select><br><br>
            <input type="hidden" name="action" value="store">
            <input type="submit" value="Crear Carrera">
        </form>
    </div>

    <div id="actualizarForm" style="display:none;">
        <form method="post" action="carrera.php">
            <input type="hidden" name="action" value="update">
            <label for="carreraId">Ingrese el ID de la carrera a actualizar:</label>
            <input type="text" name="carreraId" required>
            <input type="hidden" name="enviado" value="true">
            <input type="submit" value="Enviar">
        </form>
    </div>

    <script>
        $(document).ready(function () {
            // Mostrar formulario de Mostrar Datos de una Carrera
            $('#showBtn').click(function () {
                hideAllForms();
                $('#showForm').show();
            });

            // Mostrar formulario de Eliminar Carrera
            $('#deleteBtn').click(function () {
                hideAllForms();
                $('#deleteForm').show();
            });

            // Mostrar formulario de Crear Carrera
            $('#createBtn').click(function () {
                hideAllForms();
                $('#createForm').show();
            });

            // Mostrar formulario de Actualizar Carrera
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
