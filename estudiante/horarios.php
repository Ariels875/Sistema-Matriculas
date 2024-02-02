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

$cedulaUsuario = $_SESSION['usuario'];
$mensajeExito = '';
$mensajeError = '';
$mostrarFormulario = "";
$NivelActual = $matriculasController->ArielsControl2($cedulaUsuario);
$infoUser = $estudianteController->show($cedulaUsuario);
$idUser = $infoUser['idEstudiante'];
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="stylesheet" href="../css/styletable.css" />
    <title>horarios</title>
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
        <h1>Bienvenido aquí puedes ver tus horarios de la carrera de <?= $nombreCarrera?></h1>
        <h2>Hola <?= $nombreUser ?> Cómo va tu día?</h2>
        <?php
        if ($mostrarFormulario === 2) {
            $infoMatricula = $matriculasController->showLastMatricula($cedulaUsuario);
        
            // Obtener asignaturas matriculadas por el usuario
            $asignaturasMatriculadas = $matriculasController->getAsignaturasMatriculadas($idUser, $periodoActivo);
        
            // Definir la hora de inicio
            $horaInicio = 7;
        
            // Imprimir la tabla
            echo '<table border="1">';
            echo '<tr><th>Asignatura</th><th>Horario</th></tr>';
        
            // Iterar sobre las asignaturas matriculadas
            foreach ($asignaturasMatriculadas as $asignatura) {
                echo '<tr>';
                echo '<td>' . $asignatura['nombre_asignatura'] . '</td>';
                
                // Calcular la hora de fin sumando 1 hora al inicio
                $horaFin = $horaInicio + 1;
        
                // Mostrar el intervalo de una hora en la celda correspondiente
                echo '<td>' . sprintf('%02d', $horaInicio) . ':00 - ' . sprintf('%02d', $horaFin) . ':00</td>';
                
                echo '</tr>';
        
                // Aumentar la hora de inicio para la siguiente asignatura
                $horaInicio++;
            }
        
            echo '</table>';
        }
        
        ?>
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