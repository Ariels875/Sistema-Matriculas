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
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "asignaturaSeleccionada") {
    // Obtener el ID de la asignatura seleccionada
    $idAsignatura = $_POST["idAsignatura"];
    $infoAsignatura = $asignaturaController->showAsignatura($idAsignatura);
    $nombreAsignatura = $infoAsignatura['nombre_asignatura'];
    $data2 = array(
        "idAsignatura" => $idAsignatura,
        "nivel_carrera_idCarrera"=> $infoMatricula["asignatura_nivel_carrera_idCarrera"]
    );
    $idNivel = $matriculasController->showidNivelAsignatura($data2);

    if (!empty($idAsignatura)) {
        $numeroMatriculas = $asignaturaController->getNumeroMatriculasMateria($idUser,$idAsignatura);
        if($numeroMatriculas==2){
            $mensajeNuevo= "Usted tiene segunda matricula en $nombreAsignatura, valor a pagar $35";
            $mostrarFormulario =3;
            $monto = 35;
        }else if($numeroMatriculas==3){
            $mensajeNuevo= "Usted tiene tercera matricula en $nombreAsignatura, valor a pagar $70";
            $mostrarFormulario =3;
            $monto = 70;
        }else if ($numeroMatriculas ==1){
            $mensajeExito = "Usted no tiene pagos pendientes";

        }else{
            $mensajeNuevo= "Ya no hay cuarta matricula para $nombreAsignatura, usted debe ponerse en contacto con el Administrador";
            $monto = 35 * $numeroMatriculas;
            $mostrarFormulario =3;
        }
        

    }else{
    $mensajeError="Debe seleccionar la asignatura de la que desea saber el pago";
        
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "generarPago") {
    
    $datamatricula = array(
        "periodo_academico_idPeriodo_Academico" => $periodoActivo,
        "estudiante_idEstudiante" => $idUser,
        "asignatura_idAsignatura" => $idAsignatura,
        "asignatura_nivel_idNivel" => $idNivel,
        "asignatura_nivel_carrera_idCarrera" => $infoMatricula["asignatura_nivel_carrera_idCarrera"]
    );
    $idMatricula = $matriculasController->getIdMatricula($datamatricula);

    if($idMatricula){
        $dataPago = array(
            "monto" => $monto,
            "fecha_pago" => date("Y-m-d"),
            "estadopago_idEstado_pago" => 2,
            "matricula_idMatricula" => $idMatricula,
            "matricula_periodo_academico_idPeriodo_Academico" => $periodoActivo,
            "matricula_estudiante_idEstudiante" => $idUser,
            "matricula_asignatura_idAsignatura" => $idAsignatura,
            "matricula_asignatura_nivel_idNivel" => $idNivel,
            "matricula_asignatura_nivel_carrera_idCarrera" => $infoMatricula["asignatura_nivel_carrera_idCarrera"]
        );

        $verificarpagoexistente = $estudianteController->verificarExistenciaPago($dataPago);
        if($verificarpagoexistente == false){
            $estudianteController->storePagos($dataPago);
            $mensajeExito="Has generado tu pago con éxito!";

        }else{
            $mensajeError="Tú ya generaste este pago";
        }
    }else{
        $mensajeError="Algo salió mal, seguramente estas intentando pagar en un Periodo Academico Inactivo";
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
    <h1>Bienvenido al sistema de pagos de asignaturas de la carrera de <?= $nombreCarrera?></h1>
        <h2>Hola <? $nombreUser ?> selecciona la asignatura de la que deseas pagar</h2>
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
            <button type="submit">Informacion de esta materia</button>
        </form>

    </div>
    <div id="formulario3" style="display: none;">
        <h1>Pagos DarkZUniversity</h1>
        <h2>Una vez que hayas depositado el dinero a la cuenta del Administrador, pulsa el boton pagar para generar y verificar tu pago</h2><br>
        <h2>Tambien tendras que enviar el comprobante al correo de la universidad-> DarkZUniversity@gmail.com</h2><br><br><br><br><br><br><br><br>
        <h3><? $mensajeNuevo ?><br> para relizar su pago debe transferir dinero a la cuenta: XXXXXXXXXXXXXX</h3><br><br><br>
        <h4>Total a pagar por esta materia: <? $monto ?> $</h4>

        <Button id="generarPagoBtn" type="button">Generar Pago!</Button>
        
    
    </div>
    <button onclick="location.href='menuEstudiante.php'">Regresar</button>
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
        } else if (mostrarFormulario === 3) {
            $('#formulario2').hide();
            // Muestra el formulario 3
            $('#formulario3').show();
        }

        // Añade el evento click al botón
        $('#generarPagoBtn').click(function () {
            // Aquí puedes realizar la lógica que necesitas cuando se hace clic en el botón
            // Puedes enviar una solicitud AJAX al servidor para manejar la lógica del pago
            $.ajax({
                type: "POST",
                url: "pagos.php",
                data: { action: 'generarPago' }, // Puedes enviar datos adicionales si es necesario
                success: function (response) {
                    // Puedes manejar la respuesta del servidor aquí
                    console.log(response);
                },
                error: function (error) {
                    // Puedes manejar errores aquí
                    console.error(error);
                }
            });
        });
    });
</script>
    
</body>
</html>