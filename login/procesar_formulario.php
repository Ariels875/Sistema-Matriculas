<?php
// procesar_formulario.php

use App\Controladores\EstudianteController;
require("../vendor/autoload.php");

// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los valores de los inputs en variables PHP
    $input1 = $_POST["input1"];
    $input2 = $_POST["input2"];
    $input3 = $_POST["input3"];
    $input4 = $_POST["input4"];
    $input5 = $_POST["input5"];
    $input6 = $_POST["input6"];
    $input7 = $_POST["input7"];
    $input8 = $_POST["input8"];

    // Crear una instancia del controlador
    $controlEstudiante = new EstudianteController();

    // Llamar al método store del controlador para almacenar los datos
    $controlEstudiante->store([
        "cedula" => $input1,
        "primer_nombre" => $input2,
        "primer_apellido" => $input3,
        "fecha_nacimiento" => $input4,
        "telefono_celular" => $input5,
        "correo" => $input6,
        "direccion_domicilio" => $input7,
        "passwordd" => $input8
    ]);

    

    echo "Datos insertados correctamente en la base de datos.";
} else {
    // Si se intenta acceder al archivo sin datos del formulario, redirigir a la página del formulario
    header("Location: crear_cuenta.php");
    exit();
}
?>
