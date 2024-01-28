<?php

namespace App\Controladores;

use Database\Connection;

class AsignaturasController {

    // ... (previous code remains unchanged)
    private $connection;

    public function __construct(){
        $this->connection = Connection::getInstance()->get_database_instance();
    }

    public function indexAsignatura() {
        $stmt = $this->connection->prepare("SELECT * FROM asignatura");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function storeAsignatura($data) {
        $stmt = $this->connection->prepare("INSERT INTO asignatura (nombre_asignatura, creditos, nivel_idNivel, nivel_carrera_idCarrera, docentes_idDocentes) 
                                          VALUES(:nombre_asignatura, :creditos, :nivel_idNivel, :nivel_carrera_idCarrera, :docentes_idDocentes)");

        $stmt->bindValue(":nombre_asignatura", $data["nombre_asignatura"]);
        $stmt->bindValue(":creditos", $data["creditos"]);
        $stmt->bindValue(":nivel_idNivel", $data["nivel_idNivel"]);
        $stmt->bindValue(":nivel_carrera_idCarrera", $data["nivel_carrera_idCarrera"]);
        $stmt->bindValue(":docentes_idDocentes", $data["docentes_idDocentes"]);

        $stmt->execute();
    }

    public function showAsignatura($idAsignatura, $nivel_idNivel, $nivel_carrera_idCarrera) {
        $stmt = $this->connection->prepare("SELECT * FROM asignatura WHERE idAsignatura = :idAsignatura AND nivel_idNivel = :nivel_idNivel AND nivel_carrera_idCarrera = :nivel_carrera_idCarrera");
        $stmt->bindValue(":idAsignatura", $idAsignatura);
        $stmt->bindValue(":nivel_idNivel", $nivel_idNivel);
        $stmt->bindValue(":nivel_carrera_idCarrera", $nivel_carrera_idCarrera);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function updateAsignatura($idAsignatura, $nivel_idNivel, $nivel_carrera_idCarrera, $data) {
        try {
            $stmt = $this->connection->prepare("UPDATE asignatura SET nombre_asignatura = :nombre_asignatura, 
                                               creditos = :creditos, docentes_idDocentes = :docentes_idDocentes
                                               WHERE idAsignatura = :idAsignatura AND nivel_idNivel = :nivel_idNivel AND nivel_carrera_idCarrera = :nivel_carrera_idCarrera");
    
            $stmt->bindValue(":idAsignatura", $idAsignatura);
            $stmt->bindValue(":nivel_idNivel", $nivel_idNivel);
            $stmt->bindValue(":nivel_carrera_idCarrera", $nivel_carrera_idCarrera);
            $stmt->bindValue(":nombre_asignatura", $data["nombre_asignatura"]);
            $stmt->bindValue(":creditos", $data["creditos"]);
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

    public function destroyAsignatura($idAsignatura, $nivel_idNivel, $nivel_carrera_idCarrera) {
        $stmt = $this->connection->prepare("DELETE FROM asignatura WHERE idAsignatura = :idAsignatura AND nivel_idNivel = :nivel_idNivel AND nivel_carrera_idCarrera = :nivel_carrera_idCarrera");
        $stmt->execute([
            ":idAsignatura" => $idAsignatura,
            ":nivel_idNivel" => $nivel_idNivel,
            ":nivel_carrera_idCarrera" => $nivel_carrera_idCarrera
        ]);
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
                echo '<tr>';
                echo '<td>' . htmlspecialchars($variablebusqueda['idAsignatura']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['nombre_asignatura']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['creditos']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['nivel_idNivel']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['nivel_carrera_idCarrera']) . '</td>';
                echo '<td>' . htmlspecialchars($variablebusqueda['docentes_idDocentes']) . '</td>';
                echo '</tr>';
            }
    
            echo '</table>';
        } else {
            echo '<p>No se encontraron resultados.</p>';
        }
        
        
    }

}
