<?php

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

$EstadoPeriodo = 'Activo';
$mensajeExito = '';
$mensajeError = '';
$usuario = "555";

$infoEstudiante = $estudianteController->show($usuario);
$infoLastMatricula = $matriculasController->showLastMatricula($usuario);
$mostrarFormulario = ($infoLastMatricula ==false) ? 2 : 1;
$infoPeriodo = $carreraController->showPeriodoActivo($EstadoPeriodo);
$idCarrera = 1;
$NuevoNivel = 2;
$buscarAsignatura=array(
    "nivel" => $NuevoNivel,
    "carrera" => $idCarrera
);
$MateriasDisponibles = $asignaturaController->indexAsignaturaDisponible($buscarAsignatura);
//var_dump($MateriasDisponibles2);
$ariels1 = $matriculasController->ArielsControl($usuario);
$ariels2 = $matriculasController->ArielsControl2($usuario);

var_dump($ariels1);
var_dump($ariels2);

//$infoCarreraUsuario = $carreraController->showCarreraSpecial($infoLastMatricula);
//var_dump($infoCarreraUsuario);

