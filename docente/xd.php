<?php
require("../vendor/autoload.php");

use App\Controladores\PeriodoController;
$periodoController = new PeriodoController();

if (isset($_POST["periodo_id"])) {
    // Obtener el valor del input
    $periodoId = isset($_POST['periodo_id']) ? $_POST['periodo_id'] : '';
    if ($periodoId) {
        // Llamar a la función show del controlador
        $info = $periodoController->showPeriodoAcademico($periodoId);

        // Mostrar los datos en el formulario de actualización
        if ($info) {
            ?>
            <form method="post" action="xd.php">
                <!-- ... campos para actualizar -->
                <label for="fecha_inicio">Fecha de inicio del Periodo Academico a actualizar:</label>
                <input type="date" name="fecha_inicio" required><br><br>
                <label for="fecha_fin">Fecha de fin del Periodo Academico a actualizar:</label>
                <input type="date" name="fecha_fin" required><br><br>

                <label for="estado">estado en la que estará</label>
                <select id="estado" name="estado">
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select><br><br>

                <input type="hidden" name="idPeriodo_Academico" value="<?php echo $info["idPeriodo_Academico"]; ?>">
                <input type="submit" name="actualizar" value="Actualizar Datos">
            </form>
            <?php
        } else {
            echo "No se encontraron datos para la cédula proporcionada.";
        }
    } else {
        echo "Por favor, proporcione una cédula.";
    }
} elseif (isset($_POST["actualizar"])) {
    // Procesar el formulario de actualización
    $data = [
        "fecha_inicio" => isset($_POST["fecha_inicio"]) ? $_POST["fecha_inicio"] : null,
        "fecha_fin" => isset($_POST["fecha_fin"]) ? $_POST["fecha_fin"] : null,
        "estado" => isset($_POST["estado"]) ? $_POST["estado"] : null,
        "idPeriodo_Academico" => isset($_POST["idPeriodo_Academico"]) ? $_POST["idPeriodo_Academico"] : null,
    ];

    // Llamar a la función update del controlador
    if ($data["idPeriodo_Academico"]) {
        $periodoController->updatePeriodoAcademico($data);
        echo "Datos actualizados correctamente.";
    } else {
        echo "Error al procesar el formulario de actualización.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="stylesheet" href="../css/styletable.css" />
    <title>Editar Periodo Academico</title>
</head>
<body>
    <div id="editForm">
        <form method="post" action="xd.php">                
            <label for="periodo_id">Introduzca el id del periodo Academico:</label>
            <input type="text" name="periodo_id">
            <input type="hidden" name="action" value="xd.php">
            <input type="submit" value="Editar">
        </form>
    </div>
</body>
</html>
