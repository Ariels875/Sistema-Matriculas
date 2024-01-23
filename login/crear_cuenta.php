<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
</head>
<body>
    <h1 class="title">Crea una cuenta</h1>
    <?php
    require("../vendor/autoload.php");

    use App\Controladores\EstudianteController;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar que los valores del formulario están presentes
        if (isset($_POST["input1"]) && isset($_POST["input2"]) && isset($_POST["input3"])
            && isset($_POST["input4"]) && isset($_POST["input5"]) && isset($_POST["input6"])
            && isset($_POST["input7"]) && isset($_POST["input8"])) {

            // Recoger los valores de los inputs en variables PHP
            $input1 = $_POST["input1"];
            $input2 = $_POST["input2"];
            $input3 = $_POST["input3"];
            $input4 = $_POST["input4"];
            $input5 = $_POST["input5"];
            $input6 = $_POST["input6"];
            $input7 = $_POST["input7"];
            $input8 = $_POST["input8"];

            // Crear instancia del controlador y almacenar los datos
            $controlEstudiante = new EstudianteController();
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

            echo "Datos enviados correctamente.";
        } else {
            echo "Por favor, complete todos los campos del formulario.";
        }
    }
    ?>

    <form action="procesar_formulario.php" method="post">
        <label for="input1">Cedula:</label>
        <input type="text" name="input1" id="input1" required><br><br>

        <label for="input2">Nombre:</label>
        <input type="text" name="input2" id="input2" required><br><br>

        <label for="input3">Apellido:</label>
        <input type="text" name="input3" id="input3" required><br><br>

        <label for="input4">Fecha de Nacimiento:</label>
        <input type="date" name="input4" id="input4" required><br><br>

        <label for="input5">Numero celular:</label>
        <input type="text" name="input5" id="input5" required><br><br>

        <label for="input6">Correo:</label>
        <input type="text" name="input6" id="input6" required><br><br>

        <label for="input7">Direccion:</label>
        <input type="text" name="input7" id="input7" required><br><br>

        <label for="input8">Contraseña:</label>
        <input type="password" name="input8" id="input8" required><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>
