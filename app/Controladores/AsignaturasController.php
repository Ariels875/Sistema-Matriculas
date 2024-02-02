<?php

namespace App\Controladores;
use PDO;
use PDOException;
use Database\Connection;
use App\Controladores\DocenteController;
use App\Controladores\CarreraController;

class AsignaturasController {


    private $connection;

    public function __construct(){
        $this->connection = Connection::getInstance()->get_database_instance();
    }

    public function indexAsignatura() {
        $stmt = $this->connection->prepare("SELECT * FROM asignatura");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function indexAsignaturaDisponible($data) {
        $stmt = $this->connection->prepare("SELECT idNivel FROM nivel WHERE nivelCarrera = :nivel AND carrera_idCarrera = :carrera");
        $stmt->bindValue(":carrera", $data["nivel"]);
        $stmt->bindValue(":nivel", $data["carrera"]);
        $stmt->execute();
        $IDNivel = $stmt->fetchColumn();
    
        $stmt = $this->connection->prepare("SELECT * FROM asignatura WHERE nivel_carrera_idCarrera = :carrera AND nivel_idNivel = :idnivel");
        $stmt->bindValue(":carrera", $data["nivel"]);
        $stmt->bindValue(":idnivel", $IDNivel);  // Aquí cambié $IDNivel["idNivel"] por $IDNivel
        $stmt->execute();  // Moví esta línea para que esté antes de execute
    
        return $stmt->fetchAll();
    }
    

    public function storeAsignatura($data) {
        // Primero creamos el nivel con la carrera y el nivel que recibimos en el array $data
        $stmt = $this->connection->prepare("INSERT INTO nivel (carrera_idCarrera, nivelCarrera) VALUES(:carrera_idCarrera, :nivelCarrera)");
        $stmt->bindValue(":carrera_idCarrera", $data["nivel_carrera_idCarrera"]);
        $stmt->bindValue(":nivelCarrera", $data["nivelCarrera"]);
        $stmt->execute();
        // Luego consultamos el idNivel que se generó al crear el nivel
        $stmt = $this->connection->prepare("SELECT idNivel FROM nivel WHERE carrera_idCarrera = :carrera_idCarrera AND nivelCarrera = :nivelCarrera");
        $stmt->bindValue(":carrera_idCarrera", $data["nivel_carrera_idCarrera"]);
        $stmt->bindValue(":nivelCarrera", $data["nivelCarrera"]);
        $stmt->execute();
        // Si encontramos el idNivel, lo guardamos en una variable
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $nivel_idNivel = $row["idNivel"];
            // Finalmente insertamos la asignatura con los datos que recibimos en el array $data y el idNivel que obtuvimos
            $stmt = $this->connection->prepare("INSERT INTO asignatura (nombre_asignatura, creditos, nivel_idNivel, nivel_carrera_idCarrera, docentes_idDocentes) 
                                              VALUES(:nombre_asignatura, :creditos, :nivel_idNivel, :nivel_carrera_idCarrera, :docentes_idDocentes)");
        
            $stmt->bindValue(":nombre_asignatura", $data["nombre_asignatura"]);
            $stmt->bindValue(":creditos", $data["creditos"]);
            $stmt->bindValue(":nivel_idNivel", $nivel_idNivel);
            $stmt->bindValue(":nivel_carrera_idCarrera", $data["nivel_carrera_idCarrera"]);
            $stmt->bindValue(":docentes_idDocentes", $data["docentes_idDocentes"]);
        
            $stmt->execute();
        }
    }
    

    public function storeNivel($data) {
        $stmt = $this->connection->prepare("INSERT INTO nivel (nivelCarrera, carrera_idCarrera) 
                                          VALUES(:nivelCarrera, :carrera_idCarrera)");
    
        $stmt->bindValue(":nivelCarrera", $data["nivelCarrera"]);
        $stmt->bindValue(":carrera_idCarrera", $data["carrera_idCarrera"]);
    
        $stmt->execute();
    }

    public function ShowLastIDNivel() {
        $stmt = $this->connection->prepare("SELECT * FROM nivel ORDER BY idNivel DESC LIMIT 1");
        $stmt->execute();
    
        return $stmt->fetch();  // Cambiado a fetch() para obtener solo una fila
    }
    

    public function showAsignatura($idAsignatura) {
        $stmt = $this->connection->prepare("SELECT * FROM asignatura WHERE idAsignatura = :idAsignatura");
        $stmt->bindValue(":idAsignatura", $idAsignatura);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function updateAsignatura($data) {
        try {
            $stmt = $this->connection->prepare("UPDATE asignatura SET nombre_asignatura = :nombre_asignatura, 
                                               creditos = :creditos, docentes_idDocentes = :docentes_idDocentes,
                                               nivel_idNivel = :nivel_idNivel
                                               WHERE idAsignatura = :idAsignatura");
    
            $stmt->bindValue(":idAsignatura", $data["idAsignatura"]);
            $stmt->bindValue(":nombre_asignatura", $data["nombre_asignatura"]);
            $stmt->bindValue(":creditos", $data["creditos"]);
            $stmt->bindValue(":nivel_idNivel", $data["nivel_idNivel"]);
            //$stmt->bindValue(":nivel_carrera_idCarrera", $data["nivel_carrera_idCarrera"]);
            $stmt->bindValue(":docentes_idDocentes", $data["docentes_idDocentes"]);
    
            $stmt->execute();
    
            // Verificar si la actualización fue exitosa
            return $stmt->rowCount() > 0; // Devuelve true si se actualizó al menos una fila
        } catch (\PDOException $e) {
            // Manejo de errores, puedes registrar el error, lanzar una excepción, etc.
            error_log("Error al actualizar asignatura: " . $e->getMessage());
            return false;
        }
    }

    public function destroyAsignatura($busqueda) {
        $stmt = $this->connection->prepare("DELETE FROM asignatura WHERE idAsignatura LIKE :busqueda OR nombre_asignatura LIKE :busqueda");
        
        $busquedaParam = '%' . $busqueda . '%';
        $stmt->bindValue(":busqueda", $busquedaParam);
        $stmt->execute();
    }

    public function buscarAsignatura($busqueda) {
        $stmt = $this->connection->prepare("SELECT * FROM asignatura 
                                           WHERE nombre_asignatura LIKE :busqueda OR 
                                                 creditos LIKE :busqueda OR 
                                                 docentes_idDocentes LIKE :busqueda");

        $busquedaParam = '%' . $busqueda . '%';
        $stmt->bindValue(":busqueda", $busquedaParam);
        $stmt->execute();
        $resultados = $stmt->fetchAll();
        return $resultados;
    }

    public function ResultadosBuscarAsignatura($resultados){
        
        if (!empty($resultados)) {
            echo '<h2>Resultados de la búsqueda:</h2>';
            echo '<table>';
            echo '<tr><th>ID de la Asignatura</th><th>Nombre de la Asignatura</th><th>Creditos que ofrece</th><th>Nivel</th><th>Carrera</th><th>Docente</th></tr>';
            
            foreach ($resultados as $variablebusqueda) {
                // Obtén el verdadero nivel utilizando la función showNivelAsignatura
                $idNivelresultados = $variablebusqueda['nivel_idNivel'];
                $idverdaderoNivel = $this->showNivelAsignatura($idNivelresultados);
                $idDocenteresultados = $variablebusqueda['docentes_idDocentes'];
                $docenteController = new DocenteController;
                $nombreDocente = $docenteController->showInfoDocente($idDocenteresultados);
                $idCarreraresultados = $variablebusqueda['nivel_carrera_idCarrera'];
                $carreraController = new CarreraController;
                $nombreCarrera = $carreraController->showCarrera($idCarreraresultados);
    
                echo '<tr>';
                echo '<td>' . htmlspecialchars($variablebusqueda['idAsignatura']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['nombre_asignatura']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['creditos']) . '</td>';
                echo '<td>' . htmlspecialchars($idverdaderoNivel['nivelCarrera']) . '</td>';
                echo '<td>' . htmlspecialchars($nombreCarrera['nombre_carrera']) . '</td>';
                echo '<td>' . htmlspecialchars($nombreDocente['primer_nombre'] . ' ' . $nombreDocente['primer_apellido']) . '</td>';
                echo '</tr>';
            }
    
            echo '</table>';
        } else {
            echo '<p>No se encontraron resultados.</p>';
        }
        
        
    }

    public function indexAsignaturaTable() {
        $stmt = $this->connection->prepare("SELECT * FROM asignatura");
        $stmt->execute();
    
        $resultados = $stmt->fetchAll();
    
        if (!empty($resultados)) {
            echo '<h2>Resultados de la búsqueda:</h2>';
            echo '<table>';
            echo '<tr><th>ID de la Asignatura</th><th>Nombre de la Asignatura</th><th>Creditos que ofrece</th><th>Nivel</th><th>Carrera</th><th>Docente</th></tr>';
    
            foreach ($resultados as $variablebusqueda) {
                // Obtén el verdadero nivel utilizando la función showNivelAsignatura
                $idNivelresultados = $variablebusqueda['nivel_idNivel'];
                $idverdaderoNivel = $this->showNivelAsignatura($idNivelresultados);
                $idDocenteresultados = $variablebusqueda['docentes_idDocentes'];
                $docenteController = new DocenteController;
                $nombreDocente = $docenteController->showInfoDocente($idDocenteresultados);
                $idCarreraresultados = $variablebusqueda['nivel_carrera_idCarrera'];
                $carreraController = new CarreraController;
                $nombreCarrera = $carreraController->showCarrera($idCarreraresultados);
    
                echo '<tr>';
                echo '<td>' . htmlspecialchars($variablebusqueda['idAsignatura']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['nombre_asignatura']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['creditos']) . '</td>';
                echo '<td>' . htmlspecialchars($idverdaderoNivel['nivelCarrera']) . '</td>';
                echo '<td>' . htmlspecialchars($nombreCarrera['nombre_carrera']) . '</td>';
                echo '<td>' . htmlspecialchars($nombreDocente['primer_nombre'] . ' ' . $nombreDocente['primer_apellido']) . '</td>';
                echo '</tr>';
            }
    
            echo '</table>';
        } else {
            echo '<p>No se encontraron resultados.</p>';
        }
    }
    
    public function showNivelAsignatura($idnivel) {
        $stmt = $this->connection->prepare("SELECT * FROM nivel WHERE idNivel = :nivel_idNivel");
        $stmt->bindValue(":nivel_idNivel", $idnivel);
        $stmt->execute();
    
        return $stmt->fetch();
    }
    public function getNumeroMatriculasMateria($idEstudiante, $idAsignatura) {
        try {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM matricula 
                                                WHERE estudiante_idEstudiante = :idEstudiante
                                                AND asignatura_idAsignatura = :idAsignatura");
    
            $stmt->bindValue(":idEstudiante", $idEstudiante);
            $stmt->bindValue(":idAsignatura", $idAsignatura);
            $stmt->execute();
    
            // Obtiene el resultado
            $count = $stmt->fetchColumn();
    
            return $count;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    


}

