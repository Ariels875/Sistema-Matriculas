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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Asignatura</title>
    <link rel="stylesheet" href="../css/styleadmin.css" />
    <link rel="stylesheet" href="../css/styletable.css" />
</head>
<body>
    <button onclick="location.href='menuDocente.php'">Volver al Menú Docente</button>
    <button onclick="location.href='asignatura.php'">Atras</button>
    
    <?php

    use App\Controladores\AsignaturasController;
    use App\Controladores\CarreraController;
    use App\Controladores\DocenteController;
    require("../vendor/autoload.php");
    $asignaturaController = new AsignaturasController(); 
    $carreraController = new CarreraController(); 
    $docenteController = new DocenteController(); 
    $mensajeExito = '';
    $mensajeError = '';
    $resultados = array();
    $info = $_GET;
    $asignaturaId = isset($_GET['asignaturaId']) ? $_GET['asignaturaId'] : '';

    if ($info) { // Asegurarse de que se obtuvo información de la asignatura
        ?>
        <!-- Formulario de actualización -->
        <form method="post" action="#">
            <!-- ... campos para actualizar -->
            <label for="nombre_asignatura">Nuevo Nombre de la Asignatura:</label>
            <input type="text" name="nombre_asignatura" value="<?php echo htmlspecialchars($info["nombre_asignatura"]); ?>" required><br><br>

            <label for="creditos">Créditos:</label>
            <input type="number" name="creditos" value="<?php echo htmlspecialchars($info["creditos"]); ?>" required><br><br>

            <input type="hidden" name="asignatura_Id" value="<?php echo htmlspecialchars($info["idAsignatura"]); ?>">

            <label for="nivel">Nivel en el que estará: </label>
            <select id="nivel" name="nivel">                
                <option value="1">Primer Nivel</option>
                <option value="2">Segundo Nivel</option>                        
                <option value="3">Tercero Nivel</option>
                <option value="4">Cuarto Nivel</option>
                <option value="5">Quinto Nivel</option>
                <option value="6">Sexto Nivel</option>
                <option value="7">Septimo Nivel</option>
                <option value="8">Octavo Nivel</option>
                <option value="9">Noveno Nivel</option>
                <option value="10">Decimo Nivel</option>
            </select><br><br>
            <label for="idDocentes">Docente a cargo: </label>
            <select id="idDocentes" name="idDocentes">
                <option value="" selected>Seleccione un docente</option>
                <?php
                // Datos de opciones generados dinámicamente en PHP
                $opciones2 = $docenteController->indexDocenteAll();

                // Generar opciones utilizando un bucle en PHP
                foreach ($opciones2 as $opcion2) {
                    echo '<option value="' . htmlspecialchars($opcion2['idDocentes']) .'">' . htmlspecialchars($opcion2['primer_nombre']) ." ". htmlspecialchars($opcion2['primer_apellido']) . '</option>';
                }
                ?>
            </select><br><br>
            <input type="submit" name="actualizar" value="Actualizar Datos"> 
        </form>
        <?php
        if (isset($_POST['actualizar'])) {
            // Obtener el ID de la asignatura desde el formulario de actualización
            $idAsignatura = isset($_POST['asignatura_Id']) ? $_POST['asignatura_Id'] : '';

            // Obtener otros datos del formulario
            $nombreAsignatura = isset($_POST['nombre_asignatura']) ? $_POST['nombre_asignatura'] : '';
            $creditos = isset($_POST['creditos']) ? $_POST['creditos'] : '35';
            $nivel = isset($_POST['nivel']) ? $_POST['nivel'] : '';
            $idDocente = isset($_POST['idDocentes']) ? $_POST['idDocentes'] : '';

            $data = array(
                'idAsignatura' => $idAsignatura,
                'nombre_asignatura' => $nombreAsignatura,
                'creditos' => $creditos,
                'nivel_idNivel' => $nivel,
                'docentes_idDocentes' => $idDocente
            );
            $asignaturaController->updateAsignatura($data); // Cambiar el nombre de la función
            $mensajeExito = "Datos de la asignatura actualizados correctamente.";
        } 
    } else {
        echo "La asignatura con el ID especificado no fue encontrada.";
    }
    ?>
    <div>
    <?php
    if (!empty($mensajeExito)) {
        echo '<div class="alert success">' . $mensajeExito . '</div>';
    } elseif (!empty($mensajeError)) {
        echo '<div class="alert error">' . $mensajeError . '</div>';
    }
    ?>
    </div>
</body>
</html>