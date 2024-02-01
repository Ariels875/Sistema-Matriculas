<?php

namespace App\Controladores;
use PDO;
use Database\Connection;
use App\Controladores\DocenteController;
use App\Controladores\CarreraController;

class PeriodoController {


    private $connection;

    public function __construct(){
        $this->connection = Connection::getInstance()->get_database_instance();
    }
    public function indexPeriodoAcademico() {
        $stmt = $this->connection->prepare("SELECT * FROM periodo_academico");
        $stmt->execute();
    
        return $stmt->fetchAll();
    }
    public function storePeriodoAcademico($data) {
        $stmt = $this->connection->prepare("INSERT INTO periodo_academico (fecha_inicio, fecha_fin, estado) 
                                           VALUES (:fecha_inicio, :fecha_fin, :estado)");
    
        $stmt->bindValue(":fecha_inicio", $data["fecha_inicio"]);
        $stmt->bindValue(":fecha_fin", $data["fecha_fin"]);
        $stmt->bindValue(":estado", $data["estado"]);
    
        $stmt->execute();
    }
    
    public function ShowLastIDPeriodoAcademico() {
        $stmt = $this->connection->prepare("SELECT * FROM periodo_academico ORDER BY idPeriodo_Academico DESC LIMIT 1");
        $stmt->execute();
    
        return $stmt->fetch();
    }
    
    public function showPeriodoAcademico($idPeriodo_Academico) {
        $stmt = $this->connection->prepare("SELECT * FROM periodo_academico WHERE idPeriodo_Academico = :idPeriodo_Academico");
        $stmt->bindValue(":idPeriodo_Academico", $idPeriodo_Academico);
        $stmt->execute();
    
        return $stmt->fetch();
    }
    
    public function updatePeriodoAcademico($data) {
        try {
            $stmt = $this->connection->prepare("UPDATE periodo_academico SET fecha_inicio = :fecha_inicio, 
                                               fecha_fin = :fecha_fin, estado = :estado
                                               WHERE idPeriodo_Academico = :idPeriodo_Academico");
    
            $stmt->bindValue(":idPeriodo_Academico", $data["idPeriodo_Academico"]);
            $stmt->bindValue(":fecha_inicio", $data["fecha_inicio"]);
            $stmt->bindValue(":fecha_fin", $data["fecha_fin"]);
            $stmt->bindValue(":estado", $data["estado"]);
    
            $stmt->execute();
    
            // Verificar si la actualización fue exitosa
            return $stmt->rowCount() > 0; // Devuelve true si se actualizó al menos una fila
        } catch (\PDOException $e) {
            // Manejo de errores, puedes registrar el error, lanzar una excepción, etc.
            error_log("Error al actualizar periodo académico: " . $e->getMessage());
            return false;
        }
    }
    
    public function destroyPeriodoAcademico($idPeriodo_Academico) {
        $stmt = $this->connection->prepare("DELETE FROM periodo_academico WHERE idPeriodo_Academico = :idPeriodo_Academico");
        $stmt->bindValue(":idPeriodo_Academico", $idPeriodo_Academico);
        $stmt->execute();
    }
    
    public function buscarPeriodoAcademico($busqueda) {
        $stmt = $this->connection->prepare("SELECT * FROM periodo_academico 
                                           WHERE fecha_inicio LIKE :busqueda OR 
                                                 fecha_fin LIKE :busqueda OR 
                                                 estado LIKE :busqueda");
    
        $busquedaParam = '%' . $busqueda . '%';
        $stmt->bindValue(":busqueda", $busquedaParam);
        $stmt->execute();
    
        $resultados = $stmt->fetchAll();
        return $resultados;
    }
    
    public function ResultadosBuscarPeriodoAcademico($resultados) {
        if (!empty($resultados)) {
            echo '<h2>Resultados de la búsqueda:</h2>';
            echo '<table>';
            echo '<tr><th>ID del Periodo Académico</th><th>Fecha de Inicio</th><th>Fecha de Fin</th><th>Estado</th></tr>';
    
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

