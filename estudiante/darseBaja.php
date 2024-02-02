<?php
// darseBaja.php
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
use App\Controladores\CarreraController;
use App\Controladores\EstudianteController;
use App\Controladores\MatriculasController;
use App\Controladores\AsignaturasController;

$cedulaUsuario = $_SESSION['usuario'];
$estudianteController = new EstudianteController;
$matriculasController = new MatriculasController;
$asignaturaController = new AsignaturasController;
$carreraController = new CarreraController;
$mostrarFormulario = "";
$NivelActual = $matriculasController->ArielsControl2($cedulaUsuario);
$infoUser = $estudianteController->show($cedulaUsuario);
$nombreUser = $infoUser['primer_nombre'];
$periodoActivo= $matriculasController->getIdPeriodoAcademicoActivo();

if($NivelActual == 0){
    $mostrarFormulario = 1;
}else{
    $mostrarFormulario = 2;
    $infoMatricula= $matriculasController->showLastMatricula($cedulaUsuario);
    $data = array(
        'carrera' => $infoMatricula["asignatura_nivel_carrera_idCarrera"],
        'nivel' => $NivelActual
    );
    $AsignaturasDisponibles = $asignaturaController->indexAsignaturaDisponible($data); 
    $infoCarrera = $carreraController->showCarrera($infoMatricula['asignatura_nivel_carrera_idCarrera']);
    $nombreCarrera = $infoCarrera["nombre_carrera"];

}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "asignaturaSeleccionada") {
    // Obtener el ID de la asignatura seleccionada
    $idAsignatura = $_POST["idAsignatura"];

    $data2 = array(
        "idAsignatura" => $idAsignatura,
        "nivel_carrera_idCarrera"=> $infoMatricula["asignatura_nivel_carrera_idCarrera"]
    );
    $idNivel = $matriculasController->showidNivelAsignatura($data2);

    if (!empty($idAsignatura)) {
        

        // Construir el array $data con la información necesaria
        $data3 = array(
            "periodo_academico_idPeriodo_Academico" => $periodoActivo, // Debes implementar esta función
            "estudiante_idEstudiante" => $infoUser['idEstudiante'], // Debes implementar esta función
            "asignatura_idAsignatura" => $idAsignatura,
            "asignatura_nivel_idNivel" => $idNivel,
            "asignatura_nivel_carrera_idCarrera"=> $infoMatricula["asignatura_nivel_carrera_idCarrera"]
        );
        $infodeAsignatura= $asignaturaController->showAsignatura($idAsignatura);
        $nombreAsignatura = $infodeAsignatura["nombre_asignatura"];
        $comprobacion = $matriculasController->checkMatricula($data3);
        if($comprobacion == True){
            $matriculasController->destroyMatricula($data3);
            $mensajeExito="Te has retirado de la materia $nombreAsignatura";
        }else{
            $mensajeError="No puedes retirarte de esta asignatura si ya se terminó el periodo Academico o si no estás cursando esta materia";
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
    <title>Darse de baja</title>
</head>
<body>

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
            <h1>Hola <?= $nombreUser; ?></h1>
            <h2>Tú aún no te has matriculado en ninguna asignatura, deberías hacerlo ahora mismo!</h2>

        <button onclick="location.href='matricularse.php'">Matricularse</button>
        <button onclick="location.href='menuEstudiante.php'">Regresar al menú Principal</button>

    </div>

    <div id="formulario2" style="display: none;">
        <h1>Bienvenido al sistema de retiro de asignaturas de la carrera de <?= $nombreCarrera?></h1>
        <h2>Hola <?= $nombreUser ?> selecciona la asignatura de la que deseas retirarte</h2>
        <form method="post" action="#">
            <label for="idAsignatura">Seleccione una Carrera: </label>
            <select id="idAsignatura" name="idAsignatura">
            <option value="" selected>Seleccione una asignatura</option>
                <?php
                foreach ($AsignaturasDisponibles as $opcion) {
                    echo '<option value="' . htmlspecialchars($opcion['idAsignatura']) . '">' . htmlspecialchars($opcion['nombre_asignatura']) . '</option>';
                }
                ?>
            </select><br><br>
            <input type="hidden" name="action" value="asignaturaSeleccionada">
            <button type="submit">Retirarse de esta materia</button>
        </form>
        <button onclick="location.href='menuEstudiante.php'">Regresar</button>

    </div>
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
</body>
</html>