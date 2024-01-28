<?php
//carrerayperiodo.php

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

    <button id="index">Mostrar todos los Periodos Academicos</button>
    <button id="showBtn">Buscar Datos de un Periodo Academico</button>
    <button id="editBtn">Editar un Periodo Academico</button>
    <button id="deleteBtn">Eliminar un Periodo Academico</button>
    <button id="createBtn">Crear un Periodo Academico</button>
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
                    $resultados = $carreraController->buscarPeriodo($busqueda);
                    $carreraController->ResultadosBuscarPeriodo($resultados);
                }
                break;
        
            case 'update':
                // Lógica para actualizar los datos del estudiante

                if (isset($_POST["enviar"])) {
                    // Obtener el valor del input
                    $periodoId = isset($_POST['periodo_id']) ? $_POST['periodo_id'] : '';
                    if ($periodoId) {
                        // Llamar a la función show del controlador
                        $info=$carreraController->showPeriodo($idperiodo);
    
                        // Mostrar los datos en el formulario de actualización
                        if ($info) {
                            mostrarFormularioActualizacion($info);
                        } else {
                            echo "No se encontraron datos para la cédula proporcionada.";
                        }
                    } else {
                        echo "Por favor, proporcione una cédula.";
                    }
                } elseif (isset($_POST["actualizar"])) {
                    // Procesar el formulario de actualización
                    $periodoId = isset($_POST["periodoId"]) ? $_POST["periodoId"] : null;
                    $data = [
                        "fecha_inicio" => isset($_POST["fecha_inicio"]) ? $_POST["fecha_inicio"] : null,
                        "fecha_fin" => isset($_POST["fecha_fin"]) ? $_POST["fecha_fin"] : null,
                        "estado" => isset($_POST["estado"]) ? $_POST["estado"] : null
                    ];
    
                    // Llamar a la función update del controlador
                    if ($periodoId && $data["idEstudiante"]) {
                        $carreraController->updatePeriodo($periodoId, $data);
                        echo "Datos actualizados correctamente.";
                    } else {
                        echo "Error al procesar el formulario de actualización.";
                    }
                }
                break;
        
            case 'destroy':
                // Lógica para eliminar una carrera
                if(isset($_POST["idPeriodo_Academico"])){
                    $carreraId = isset($_POST['idPeriodo_Academico']) ? $_POST['idPeriodo_Academico'] : '';
                    $carreraController->destroyCarrera($carreraId);
        
                    $mensajeExito = "La carrera ha sido eliminada";
                }else{
                    $mensajeError = "Por favor, complete todos los campos del formulario.";
                }
                break;
        
            case 'store':
                // Lógica para almacenar los datos del estudiante
                if(isset($_POST["nombre_carrera"])){
                    $data = array(
                        'fecha_inicio' => $_POST['fecha_inicio'],
                        'fecha_fin' => $_POST['fecha_fin'],
                        'estado' => $_POST['estado']
                        // ... (otras variables)
                    );
                    $carreraController->storePeriodo($data);
                    $mensajeExito = "Datos enviados correctamente.";
                }else{
                    $mensajeError = "Por favor, complete todos los campos del formulario.";
                }
                break;
            case 'index':
                $carreraController->indexPeriodo();
            
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
        <form method="post" action="periodo.php">
            <!-- ... campos para mostrar datos -->
            <input type="hidden" name="action" value="show">
            <label for="busqueda">Buscar por fecha o estado del periodo Academico:</label>
            <input type="text" name="busqueda">

            <input type="submit" value="Mostrar Datos">
        </form>
    </div>

    <div id="editForm" style="display:none;">
        <form method="post" action="periodo.php">
            <!-- ... campos para editar -->
            <input type="hidden" name="action" value="edit">
            <input type="submit" value="Editar">
        </form>
    </div>

    <div id="deleteForm" style="display:none;">
        <form method="post" action="periodo.php">
            <!-- ... campos para eliminar -->
            <input type="hidden" name="action" value="destroy">
            <label for="nombre_carrera">Nombre o ID del periodo academico a eliminar:</label>
            <input type="text" name="nombre_carrera" required><br><br>

            <input type="submit" value="Eliminar carrera">
        </form>
    </div>

    <div id="createForm" style="display:none;">
        <form method="post" action="periodo.php">
            <!-- ... campos para crear -->
            <label for="fecha_inicio">Fecha de inicio del Periodo Academico a crear:</label>
            <input type="date" name="fecha_inicio" required><br><br>
            <label for="fecha_fin">Fecha de fin del Periodo Academico a crear:</label>
            <input type="date" name="fecha_fin" required><br><br>

            <label for="estado">estado en la que estará</label>
            <select id="estado" name="estado">
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select><br><br>

            <input type="hidden" name="action" value="store">
            <input type="submit" value="Crear Periodo Academico">
        
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Mostrar formulario de Mostrar Datos de una Carrera
            $('#showBtn').click(function() {
                hideAllForms();
                $('#showForm').show();
            });

            // Mostrar formulario de Editar Carrera
            $('#editBtn').click(function() {
                hideAllForms();
                $('#editForm').show();
            });

            // Mostrar formulario de Eliminar Carrera
            $('#deleteBtn').click(function() {
                hideAllForms();
                $('#deleteForm').show();
            });

            // Mostrar formulario de Crear Carrera
            $('#createBtn').click(function() {
                hideAllForms();
                $('#createForm').show();
            });

            function hideAllForms() {
                // Ocultar todos los formularios
                $('#showForm, #editForm, #deleteForm, #createForm').hide();
            }
        });
    </script>

</body>
</html>
