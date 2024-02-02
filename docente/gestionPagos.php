<?php
session_start();

// Verificar si la sesión está iniciada y si el rol es docente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'docente') {
    // Si no hay sesión o el rol no es docente, redirigir al formulario de inicio de sesión
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
use App\Controladores\EstudianteController;
require("../vendor/autoload.php");
$estudianteController = new EstudianteController();

// Verificar si se envió el formulario para buscar pagos de un alumno
if (isset($_POST['buscar_alumno'])) {
    $alumno_id = $_POST['alumno_id'];

    // Lógica para obtener todos los pagos del alumno con el ID $alumno_id
    $pagos_alumno = $estudianteController->obtenerPagosPorAlumno($alumno_id);

    // Mostrar los resultados
    if ($pagos_alumno) {
        foreach ($pagos_alumno as $pago) {
            // Mostrar información de cada pago
            echo "ID Pago: " . $pago['idPagos'] . "<br>";
            echo "Monto: $" . $pago['monto'] . "<br>";
            echo "Fecha de Pago: " . $pago['fecha_pago'] . "<br>";
            echo "<hr>";
        }
    } else {
        echo "No se encontraron pagos para el alumno con ID $alumno_id";
    }
}

// Verificar si se envió el formulario para editar un pago
if (isset($_POST['editar_pago'])) {
    $pago_id = $_POST['pago_id'];

    // Lógica para obtener la información del pago con el ID $pago_id
    $pago_a_editar = $estudianteController->obtenerInformacionPago($pago_id);

    // Mostrar el formulario de edición
    if ($pago_a_editar) {
        ?>
        <form method="post" action="gestionPagos.php">
            <!-- Campos para editar -->
            <label for="estadopago_idEstado_pago">Ya esta pagado?:</label>
            <select id="estadopago_idEstado_pago" name="estadopago_idEstado_pago">                
                <option value="1">PAGADO</option>
                <option value="2">NO PAGADO</option>                        
            </select><br><br>

            <input type="hidden" name="pago_id" value="<?php echo $pago_id; ?>">
            <input type="submit" name="guardar_edicion" value="Guardar Edición">
        </form>
        <?php
    } else {
        echo "No se encontró el pago con ID $pago_id para editar.";
    }
}

// Verificar si se envió el formulario para guardar la edición de un pago
if (isset($_POST['guardar_edicion'])) {
    $pago_id = $_POST['pago_id'];
    $estadopago_idEstado_pago = $_POST['estadopago_idEstado_pago'];

    // Lógica para actualizar el pago con la nueva información
    $resultado_actualizacion = $estudianteController->actualizarPago($$pago_id,$estadopago_idEstado_pago);

    // Mostrar mensaje de éxito o error
    if ($resultado_actualizacion) {
        echo "El pago con ID $pago_id se actualizó correctamente.";
    } else {
        echo "Error al actualizar el pago con ID $pago_id.";
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
    <title>Gestión de Pagos</title>
</head>
<body>
    <h1>Gestión de Pagos</h1>

    <!-- Formulario para buscar pagos de un alumno -->
    <form method="post" action="gestionPagos.php">
        <label for="alumno_id">ID del Alumno:</label>
        <input type="text" name="alumno_id" required>
        <input type="submit" name="buscar_alumno" value="Buscar Pagos">
    </form><br><br><br>

    <!-- Formulario para editar un pago por su ID -->
    <form method="post" action="gestionPagos.php">
        <label for="pago_id">ID del Pago a Editar:</label>
        <input type="text" name="pago_id" required>
        <input type="submit" name="editar_pago" value="Editar Pago">
    </form>
    
    <div>    
        <?php
        $form_type = isset($_POST['form_type']) ? $_POST['form_type'] : '';
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $form_type === 'buscarEstudiante') {
            // Solo procesa la búsqueda si se ha enviado el formulario
            if (isset($_POST['busqueda'])) {
                $busqueda = $_POST['busqueda'];
                $resultados = $estudianteController->buscarEstudiante($busqueda);

                // Muestra los resultados en la página.
                if (!empty($resultados)) {
                    echo '<h2>Resultados de la búsqueda:</h2>';
                    echo '<table>';
                    echo '<tr><th>Cédula</th><th>Nombre</th><th>Apellido</th><th>Fecha de Nacimiento</th><th>Correo</th><th>Direccion</th></tr>';

                    foreach ($resultados as $estudiante) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($estudiante['cedula']) . '</td>';
                        echo '<td>' . htmlspecialchars($estudiante['primer_nombre']) . '</td>';
                        echo '<td>' . htmlspecialchars($estudiante['primer_apellido']) . '</td>';
                        echo '<td>' . htmlspecialchars($estudiante['fecha_nacimiento']) . '</td>';
                        echo '<td>' . htmlspecialchars($estudiante['correo']) . '</td>';
                        echo '<td>' . htmlspecialchars($estudiante['direccion_domicilio']) . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                } else {
                    echo '<p>No se encontraron resultados.</p>';
                }
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="form_type" value="buscarEstudiante">

            <label for="busqueda">Buscar Estudiante:</label>
            <input type="text" name="busqueda" id="busqueda" required>
            <button type="submit">Buscar</button>
        </form>
    </div>

</body>
</html>
