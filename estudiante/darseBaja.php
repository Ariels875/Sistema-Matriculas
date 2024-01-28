<?php
use App\Controladores\EstudianteController;
use App\Controladores\MatriculasController;
require("../vendor/autoload.php");
$cedulaUsuario = $_POST["usuario"];
$estudianteController = new EstudianteController;
$matriculasController = new MatriculasController;

$infoEstudiante = $estudianteController->show($cedulaUsuario);
$idEstudiante = $infoEstudiante["idEstudiante"];
$infoMatriculaEstudiante = $matriculasController->showMatriculaEstudiante($idEstudiante);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darse de baja</title>
</head>
<body>
    <div>
        <h1>Seleccione la materia de la que desea retirarse:</h1>

    </div>
</body>
</html>