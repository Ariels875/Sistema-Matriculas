<?php
session_start();

// Verificar si la sesión está iniciada y si el rol es estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'estudiante') {
    // Si no hay sesión o el rol no es estudiante, redirigir al formulario de inicio de sesión
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
require("../vendor/autoload.php");
use App\Controladores\EstudianteController;
use App\Controladores\AsignaturasController;
use App\Controladores\CarreraController;
use App\Controladores\DocenteController;
use App\Controladores\MatriculasController;
$asignaturaController = new AsignaturasController(); 
$carreraController = new CarreraController(); 
$docenteController = new DocenteController(); 
$matriculasController = new MatriculasController();
$estudianteController = new EstudianteController();

$mensajeExito = '';
$mensajeError = '';
$EstadoPeriodo = 'Activo';
$usuario = $_SESSION['usuario'];
$infoEstudiante = $estudianteController->show($usuario);
$infoLastMatricula = $matriculasController->showLastMatricula($usuario);
$mostrarFormulario = "";
if ($infoLastMatricula==False){
    $mostrarFormulario = 2;
}else{
    $mostrarFormulario = 1;
    if (array_key_exists('asignatura_nivel_carrera_idCarrera', $infoLastMatricula)) {
        // Obtener el valor de la clave "asignatura_nivel_carrera_idCarrera"
        $idCarrera = $infoLastMatricula['asignatura_nivel_carrera_idCarrera'];
        $nivelAnterior = $matriculasController->ArielsControl($usuario);
        $nivelActual = $matriculasController->ArielsControl2($usuario);
        if($nivelActual != 0){
            $nivelVerdadero = $nivelActual;
        }else{
            $nivelVerdadero = $nivelAnterior + 1;
        }

        $infoCarrera2 = $carreraController->showCarrera($idCarrera);
        
        $buscarAsignatura=array(
            "nivel" => $nivelVerdadero,
            "carrera" => $idCarrera
        );
        $buscarAsignaturaNivelAnterior=array(
            "nivel" => $nivelAnterior,
            "carrera" => $idCarrera
        );
        $MateriasDisponibles = $asignaturaController->indexAsignaturaDisponible($buscarAsignatura);
        $MateriasDisponiblesNivelAnterior = $asignaturaController->indexAsignaturaDisponible($buscarAsignaturaNivelAnterior);


    } else {
        // La clave no existe en el array
        echo "La clave 'asignatura_nivel_carrera_idCarrera' no está presente en el array.";
    }

}
var_dump($nivelAnterior);
var_dump($nivelVerdadero);

$infoPeriodo = $carreraController->showPeriodoActivo($EstadoPeriodo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se recibió la acción 'carreraSeleccionada'
    if (isset($_POST['action']) && $_POST['action'] === 'carreraSeleccionada') {
        // Recupera la opción seleccionada en el formulario2
        $idCarreraSeleccionada = isset($_POST['idCarrera']) ? $_POST['idCarrera'] : null;
        $infoCarrera = $matriculasController->consulta_base_datos($idCarreraSeleccionada);
        $mensajeExito="Bien hecho! Ahora pulsa Continuar";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se recibió la acción 'carreraSeleccionada'
    if (isset($_POST['action']) && $_POST['action'] === 'generarMatricula') {
        // Recupera la opción seleccionada en el formulario2
        $idAsignaturaSeleccionada = isset($_POST['idAsignatura']) ? $_POST['idAsignatura'] : null;
        
        $infoAsignatura = $asignaturaController->showAsignatura($idAsignaturaSeleccionada);
        
        $data = array(
            'fecha_matricula' => date("Y-m-d"),
            'periodo_academico_idPeriodo_Academico' => $infoPeriodo['idPeriodo_Academico'],
            'estudiante_idEstudiante' => $infoEstudiante["idEstudiante"],
            'asignatura_idAsignatura' => ($_POST['idAsignatura']),
            'asignatura_nivel_idNivel' => $infoAsignatura["nivel_idNivel"],
            'asignatura_nivel_carrera_idCarrera' => $infoAsignatura["nivel_carrera_idCarrera"]
        );
        if($matriculasController->checkMatricula($data)==True){
            $mensajeError="Tú ya estás matriculado en esta materia";
        }else{
            $matriculasController->storeMatricula($data);
            $mensajeExito="Tú te has matriculado en una de las mejores materias de la carrera";
            
        }

    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="stylesheet" href="../css/styletable.css" />
    <title>Matricularse :D</title>
</head>
<body>
    <h1>Bienvenido al sistema de matrículas!</h1>
    <div>
        <?php
        if (!empty($mensajeExito)) {
            echo '<div class="alert success">' . $mensajeExito . '</div>';
        } elseif (!empty($mensajeError)) {
            echo '<div class="alert error">' . $mensajeError . '</div>';
        }
        ?>
    </div>
    <div id="formulario1" style="display: none;">
         <form method="post" action="matricularse.php">   
            <h2>Selecciona las materias de tu carrera <?= $infoCarrera2["nombre_carrera"]?> en las que deseas matricularte</h2>
                <label for="idAsignatura">Seleccione una Asignatura: </label>
                <select id="idAsignatura" name="idAsignatura">
                    <?php
                    foreach ($MateriasDisponibles as $opcion4) {
                        echo '<option value="' . htmlspecialchars($opcion4['idAsignatura']) . '">' . htmlspecialchars($opcion4['nombre_asignatura']) . '</option>';
                    }
                    ?>
                </select><br><br>
                <input type="hidden" name="action" value="generarMatricula">
                <button type="submit">Matricularse en esta materia</button>
        </form>
        <form method="post" action="matricularse.php">   
            <h2>Si no has aprobado alguna de las materias del nivel anterior, matriculate aquí</h2>
                <label for="idAsignatura">Seleccione una Asignatura: </label>
                <select id="idAsignatura" name="idAsignatura">
                    <?php
                    foreach ($MateriasDisponiblesNivelAnterior as $opcion5) {
                        echo '<option value="' . htmlspecialchars($opcion5['idAsignatura']) . '">' . htmlspecialchars($opcion5['nombre_asignatura']) . '</option>';
                    }
                    ?>
                </select><br><br>
                <input type="hidden" name="action" value="generarMatricula">
                <button type="submit">Matricularse en esta materia</button>
        </form>
        <button onclick="location.href='menuEstudiante.php'">Regresar al menú Principal</button>

    </div>

    <div id="formulario2" style="display: none;">
        <h2>Una vez que hayas seleccionado la carrera, pulsa el boton Seleccionar Carrera y luego el boton Continuar</h2>
        <form method="post" action="#">
            <label for="idCarrera">Seleccione una Carrera: </label>
            <select id="idCarrera" name="idCarrera">
            <option value="" selected>Seleccione una carrera</option>
                <?php
                $opciones = $carreraController->indexCarreraAlloptions();
                foreach ($opciones as $opcion) {
                    echo '<option value="' . htmlspecialchars($opcion['idCarrera']) . '">' . htmlspecialchars($opcion['nombre_carrera']) . '</option>';
                }
                ?>
            </select><br><br>
            <input type="hidden" name="action" value="carreraSeleccionada">
            <input type="submit" value="Seleccionar Carrera">
        </form>
        <br><br><button id="seleccionarCarreraBtn" type="button">Continuar</button>
        <button onclick="location.href='menuEstudiante.php'">Regresar</button>

    </div>

    <!-- Script jQuery -->
    <script>
        $(document).ready(function () {
            // En otra parte del código donde deseas mostrar el formulario
            var mostrarFormulario = <?php echo json_encode($mostrarFormulario); ?>;
            if (mostrarFormulario === 1) {
                // Muestra el formulario 1
                $('#formulario1').show();
            } else if (mostrarFormulario === 2) {
                // Muestra el formulario 2
                $('#formulario2').show();
            }
        });
    </script>
    <div id="formulario3" style="display: none;">
        <form method="post" action="matricularse.php">
            <label for="idAsignatura">Seleccione una Asignatura: </label>
            <select id="idAsignatura" name="idAsignatura">
                <?php
                foreach ($infoCarrera as $opcion2) {
                    echo '<option value="' . htmlspecialchars($opcion2['idAsignatura']) . '">' . htmlspecialchars($opcion2['nombre_asignatura']) . '</option>';
                }
                ?>
            </select><br><br>            
            <input type="hidden" name="action" value="generarMatricula">
            <button type="submit">Matricularse en esta materia</button>
        </form>
        <button onclick="location.href='menuEstudiante.php'">Regresar al menú Principal</button>
    </div>

    <!-- Script jQuery -->
    <script>
        $(document).ready(function () {
            // Evento de clic para el botón "Seleccionar Carrera"
            $('#seleccionarCarreraBtn').click(function () {
                // Oculta el formulario 2
                $('#formulario2').hide();
                // Muestra el formulario 3
                $('#formulario3').show();
            });
        });
    </script>
</body>
</html>