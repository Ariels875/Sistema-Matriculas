<?php

namespace App\Controladores;

use Database\Connection;

class MatriculasController {

    private $connection;

    public function __construct(){
        $this->connection = Connection::getInstance()->get_database_instance();
    }

    public function indexCarrera() {
        $stmt = $this->connection->prepare("SELECT * FROM carrera");
        $stmt->execute();
    
        $resultados = $stmt->fetchAll();
    
        // Llamar a la función para mostrar los resultados
        $this->ResultadosBuscarCarrera($resultados);
    }
    
    public function storeCarrera($data) {
        $stmt = $this->connection->prepare("INSERT INTO carrera (nombre_carrera, facultad, modalidad) 
                                          VALUES(:nombre_carrera, :facultad, :modalidad)");

        $stmt->bindValue(":nombre_carrera", $data["nombre_carrera"]);
        $stmt->bindValue(":facultad", $data["facultad"]);
        $stmt->bindValue(":modalidad", $data["modalidad"]);

        $stmt->execute();
    }
    

    public function showCarrera($idCarrera) {
        $stmt = $this->connection->prepare("SELECT * FROM carrera WHERE idCarrera = :idCarrera");
        $stmt->bindValue(":idCarrera", $idCarrera);
        $stmt->execute();
        $info = $stmt->fetch();
        return $info;
    }


    public function showMatriculaEstudiante($idEstudiantea) {
        $stmt = $this->connection->prepare("SELECT * FROM matricula WHERE estudiante_idEstudiantea = :idEstudiantea");
        $stmt->bindValue(":idEstudiantea", $idEstudiantea);
        $stmt->execute();
        $info = $stmt->fetch();
        return $info;
    }

    public function updateCarrera($idCarrera, $data) {
        try {
            $stmt = $this->connection->prepare("UPDATE carrera SET nombre_carrera = :nombre_carrera, 
                                               facultad = :facultad, modalidad = :modalidad
                                               WHERE idCarrera = :idCarrera");
    
            $stmt->bindValue(":idCarrera", $idCarrera);
            $stmt->bindValue(":nombre_carrera", $data["nombre_carrera"]);
            $stmt->bindValue(":facultad", $data["facultad"]);
            $stmt->bindValue(":modalidad", $data["modalidad"]);
    
            $stmt->execute();
    
            // Verificar si la actualización fue exitosa
            return $stmt->rowCount() > 0; // Devuelve true si se actualizó al menos una fila
        } catch (\PDOException $e) {
            // Manejo de errores, puedes registrar el error, lanzar una excepción, etc.
            error_log("Error al actualizar carrera: " . $e->getMessage());
            return false;
        }
    }
    

    public function destroyCarrera($idCarrera) {
        $stmt = $this->connection->prepare("DELETE FROM carrera WHERE idCarrera LIKE :idCarrera OR nombre_carrera LIKE :idCarrera");
        $stmt->execute([":idCarrera" => $idCarrera]);
    }

    public function buscarCarrera($busqueda) {
        $stmt = $this->connection->prepare("SELECT * FROM carrera 
                                           WHERE nombre_carrera LIKE :busqueda OR 
                                                 facultad LIKE :busqueda OR 
                                                 modalidad LIKE :busqueda");

        $busquedaParam = '%' . $busqueda . '%';
        $stmt->bindValue(":busqueda", $busquedaParam);
        $stmt->execute();
        $resultados = $stmt->fetchAll();
        return $resultados;
    }
    public function ResultadosBuscarCarrera($resultados){
        
        if (!empty($resultados)) {
            echo '<h2>Resultados de la búsqueda:</h2>';
            echo '<table>';
            echo '<tr><th>ID de la carrera</th><th>Nombre de la carrera</th><th>Facultad en la que se encuentra</th><th>Modalidad</th></tr>';
            
            foreach ($resultados as $variablebusqueda) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($variablebusqueda['idCarrera']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['nombre_carrera']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['facultad']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['modalidad']) . '</td>';
                echo '</tr>';
            }
    
            echo '</table>';
        } else {
            echo '<p>No se encontraron resultados.</p>';
        }
        
        
    }
    public function indexPeriodo() {
        $stmt = $this->connection->prepare("SELECT * FROM periodo_academico");
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function storePeriodo($data) {
        $stmt = $this->connection->prepare("INSERT INTO periodo_academico (fecha_inicio, fecha_fin, estado) 
                                          VALUES(:fecha_inicio, :fecha_fin, :estado)");

        $stmt->bindValue(":fecha_inicio", $data["fecha_inicio"]);
        $stmt->bindValue(":fecha_fin", $data["fecha_fin"]);
        $stmt->bindValue(":estado", $data["estado"]);

        $stmt->execute();
    }

    public function showPeriodo($idPeriodo_Academico) {
        $stmt = $this->connection->prepare("SELECT * FROM periodo_academico WHERE idPeriodo_Academico = :idPeriodo_Academico");
        $stmt->bindValue(":idPeriodo_Academico", $idPeriodo_Academico);
        $stmt->execute();
        $info = $stmt->fetch();
        return $info;
    }

    public function updatePeriodo($idPeriodo_Academico, $data) {
        $stmt = $this->connection->prepare("UPDATE periodo_academico SET fecha_inicio = :fecha_inicio, 
                                           fecha_fin = :fecha_fin, estado = :estado
                                           WHERE idPeriodo_Academico = :idPeriodo_Academico");

        $stmt->bindValue(":idPeriodo_Academico", $idPeriodo_Academico);
        $stmt->bindValue(":fecha_inicio", $data["fecha_inicio"]);
        $stmt->bindValue(":fecha_fin", $data["fecha_fin"]);
        $stmt->bindValue(":estado", $data["estado"]);

        $stmt->execute();
    }

    public function destroyPeriodo($idPeriodo_Academico) {
        $stmt = $this->connection->prepare("DELETE FROM periodo_academico WHERE idPeriodo_Academico LIKE :idPeriodo_Academico OR nombre_carrera LIKE :idPeriodo_Academico");
        $stmt->execute([":idPeriodo_Academico" => $idPeriodo_Academico]);
    }

    public function buscarPeriodo($busqueda) {
        $stmt = $this->connection->prepare("SELECT * FROM periodo_academico 
                                           WHERE estado LIKE :busqueda OR 
                                                 fecha_inicio LIKE :busqueda OR 
                                                 fecha_fin LIKE :busqueda");

        $busquedaParam = '%' . $busqueda . '%';
        $stmt->bindValue(":busqueda", $busquedaParam);
        $stmt->execute();
        $resultados = $stmt->fetchAll();
        return $resultados;
    }
    public function ResultadosBuscarPeriodo($resultados){
        
        if (!empty($resultados)) {
            echo '<h2>Resultados de la búsqueda:</h2>';
            echo '<table>';
            echo '<tr><th>ID del Periodo Academico</th><th>fecha inicio</th><th>fecha fin</th><th>estado</th></tr>';
            
            foreach ($resultados as $variablebusqueda) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($variablebusqueda['idPeriodo_Academico']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['fecha_inicio']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['fecha_fin']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['estado']) . '</td>';
                echo '</tr>';
            }
    
            echo '</table>';
        } else {
            echo '<p>No se encontraron resultados.</p>';
        }
        
        
    }
}

