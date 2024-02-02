<?php
namespace App\Controladores;
use PDO;
use PDOException;
use Database\Connection;
use App\Controladores\EstudianteController;


class MatriculasController {

    private $connection;

    public function __construct(){
        $this->connection = Connection::getInstance()->get_database_instance();
    }

    public function showLastMatricula($Usuario) {
        $estudianteController = new EstudianteController;
        $dataUsuario = $estudianteController->show($Usuario);

        $idUsuario = $dataUsuario["idEstudiante"];
        try {
            $stmt = $this->connection->prepare("SELECT * FROM matricula WHERE estudiante_idEstudiante = :usuario");
            $stmt->bindValue(":usuario", $idUsuario);
            $stmt->execute();
            $info = $stmt->fetch();
            return $info;
        } catch (PDOException $e) {
            echo "El estudiante no se ha matriculado antes " . $e->getMessage();
            return array();
        }
    }
    

    function consulta_base_datos($variable) {
        try {

            // Hacer la primera consulta
            $stmt = $this->connection->prepare("SELECT idNivel FROM nivel WHERE nivelCarrera = 1 AND carrera_idCarrera = :variable");

            $stmt->bindParam(':variable', $variable);
            $stmt->execute();
            $VarObtenidaConsulta = $stmt->fetchColumn();

            // Hacer la segunda consulta
            $stmt = $this->connection->prepare("SELECT * FROM asignatura WHERE nivel_carrera_idCarrera = :variable AND nivel_idNivel = :VarObtenidaConsulta");
            $stmt->bindParam(':variable', $variable);
            $stmt->bindParam(':VarObtenidaConsulta', $VarObtenidaConsulta);
            $stmt->execute();

            // Obtener los datos disponibles
            $datos_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Devolver los datos disponibles
            return $datos_disponibles;
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    public function storeMatricula($data) {
        $stmt = $this->connection->prepare("INSERT INTO matricula (fecha_matricula, periodo_academico_idPeriodo_Academico, estudiante_idEstudiante, asignatura_idAsignatura
        , asignatura_nivel_idNivel, asignatura_nivel_carrera_idCarrera) 
                                          VALUES(:fecha_matricula, :periodo_academico_idPeriodo_Academico, :estudiante_idEstudiante, :asignatura_idAsignatura, :asignatura_nivel_idNivel, :asignatura_nivel_carrera_idCarrera)");

        $stmt->bindValue(":fecha_matricula", $data["fecha_matricula"]);
        $stmt->bindValue(":periodo_academico_idPeriodo_Academico", $data["periodo_academico_idPeriodo_Academico"]);
        $stmt->bindValue(":estudiante_idEstudiante", $data["estudiante_idEstudiante"]);
        $stmt->bindValue(":asignatura_idAsignatura", $data["asignatura_idAsignatura"]);
        $stmt->bindValue(":asignatura_nivel_idNivel", $data["asignatura_nivel_idNivel"]);
        $stmt->bindValue(":asignatura_nivel_carrera_idCarrera", $data["asignatura_nivel_carrera_idCarrera"]);

        $stmt->execute();
    }

    public function checkMatricula($data) {
        try {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM matricula 
                                                WHERE periodo_academico_idPeriodo_Academico = :periodo_academico_idPeriodo_Academico
                                                AND estudiante_idEstudiante = :estudiante_idEstudiante
                                                AND asignatura_idAsignatura = :asignatura_idAsignatura
                                                AND asignatura_nivel_idNivel = :asignatura_nivel_idNivel
                                                AND asignatura_nivel_carrera_idCarrera = :asignatura_nivel_carrera_idCarrera");

            $stmt->bindValue(":periodo_academico_idPeriodo_Academico", $data["periodo_academico_idPeriodo_Academico"]);
            $stmt->bindValue(":estudiante_idEstudiante", $data["estudiante_idEstudiante"]);
            $stmt->bindValue(":asignatura_idAsignatura", $data["asignatura_idAsignatura"]);
            $stmt->bindValue(":asignatura_nivel_idNivel", $data["asignatura_nivel_idNivel"]);
            $stmt->bindValue(":asignatura_nivel_carrera_idCarrera", $data["asignatura_nivel_carrera_idCarrera"]);
    
            $stmt->execute();
    
            // Obtiene el resultado
            $count = $stmt->fetchColumn();
    
            // Si hay al menos una fila con los mismos valores, retorna true, de lo contrario, retorna false
            return $count > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function showAllMatriculas($Usuario) {
        $estudianteController = new EstudianteController;
        $dataUsuario = $estudianteController->show($Usuario);
    
        $idUsuario = $dataUsuario["idEstudiante"];
        try {
            $stmt = $this->connection->prepare("SELECT * FROM matricula WHERE estudiante_idEstudiante = :usuario");
            $stmt->bindValue(":usuario", $idUsuario);
            $stmt->execute();
            $info = $stmt->fetchAll();  // Obtener todas las filas
            return $info;
        } catch (PDOException $e) {
            echo "Error al obtener las matrículas del estudiante " . $e->getMessage();
            return array();
        }
    }

    public function destroyMatricula($data) {
        $stmt = $this->connection->prepare("DELETE FROM matricula 
                                                WHERE periodo_academico_idPeriodo_Academico = :periodo_academico_idPeriodo_Academico
                                                AND estudiante_idEstudiante = :estudiante_idEstudiante
                                                AND asignatura_idAsignatura = :asignatura_idAsignatura
                                                AND asignatura_nivel_idNivel = :asignatura_nivel_idNivel
                                                AND asignatura_nivel_carrera_idCarrera = :asignatura_nivel_carrera_idCarrera");
        
        $stmt->bindValue(":periodo_academico_idPeriodo_Academico", $data["periodo_academico_idPeriodo_Academico"]);
        $stmt->bindValue(":estudiante_idEstudiante", $data["estudiante_idEstudiante"]);
        $stmt->bindValue(":asignatura_idAsignatura", $data["asignatura_idAsignatura"]);
        $stmt->bindValue(":asignatura_nivel_idNivel", $data["asignatura_nivel_idNivel"]);
        $stmt->bindValue(":asignatura_nivel_carrera_idCarrera", $data["asignatura_nivel_carrera_idCarrera"]);
        $stmt->execute();

    }
    
    public function showNivelCarrera($idnivel) {
        $stmt = $this->connection->prepare("SELECT nivelCarrera FROM nivel WHERE idNivel = :nivel_idNivel");
        $stmt->bindValue(":nivel_idNivel", $idnivel);
        $stmt->execute();
    
        return $stmt->fetch();
    }
    public function showidNivelAsignatura($data) {
        $stmt = $this->connection->prepare("SELECT nivel_idNivel FROM asignatura WHERE idAsignatura = :idAsignatura AND nivel_carrera_idCarrera = :nivel_carrera_idCarrera");
        $stmt->bindValue(":idAsignatura", $data['idAsignatura']);
        $stmt->bindValue(":nivel_carrera_idCarrera", $data['nivel_carrera_idCarrera']);

        $stmt->execute();
        $idNivel = $stmt->fetchColumn();
        return $idNivel;
    }
    public function getIdPeriodoAcademicoActivo() {
        try {
            $stmt = $this->connection->prepare("SELECT idPeriodo_Academico FROM periodo_academico WHERE estado = 'Activo'");
            $stmt->execute();
            $idPeriodoActivo = $stmt->fetchColumn();

            return $idPeriodoActivo;
        } catch (PDOException $e) {
            echo "Error al obtener el id del periodo académico activo: " . $e->getMessage();
            return null;
        }
    }
    
        // Función para obtener el id del último periodo académico inactivo
    public function getIdUltimoPeriodoInactivo() {
        try {
            $stmt = $this->connection->prepare("SELECT idPeriodo_Academico FROM periodo_academico WHERE estado = 'Inactivo' ORDER BY idPeriodo_Academico DESC LIMIT 1");
            $stmt->execute();
            $idUltimoPeriodoInactivo = $stmt->fetchColumn();
    
            return $idUltimoPeriodoInactivo;
        } catch (PDOException $e) {
            echo "Error al obtener el id del último periodo académico inactivo: " . $e->getMessage();
            return null;
        }
    }
    public function ArielsControl($Usuario) {
        $AllMatriculas = $this->showAllMatriculas($Usuario);
        $idUltimoPeriodoInactivo = $this->getIdUltimoPeriodoInactivo();
        $nivelAnterior = 0;
    
        if (count($AllMatriculas) > 0 && is_array(current($AllMatriculas))) {
            // Es bidimensional
            foreach ($AllMatriculas as $fila) {
                // Comparar el valor periodo_academico_idPeriodo_Academico con $getIdUltimoPeriodoInactivo
                if ($fila['periodo_academico_idPeriodo_Academico'] == $idUltimoPeriodoInactivo) {
                    // Almacenar asignatura_nivel_idNivel de la tabla en la que encontró la coincidencia
                    $nivelAnterior = $this->showNivelCarrera($fila['asignatura_nivel_idNivel']);
                    $nivelAnterior = $nivelAnterior["nivelCarrera"];

                }
            }
        } else {
            // Es unidimensional
            // Comparar el valor periodo_academico_idPeriodo_Academico con $getIdUltimoPeriodoInactivo
            if ($AllMatriculas['periodo_academico_idPeriodo_Academico'] == $idUltimoPeriodoInactivo) {
                // Almacenar asignatura_nivel_idNivel de la tabla en la que encontró la coincidencia
                $nivelAnterior = $this->showNivelCarrera($AllMatriculas['asignatura_nivel_idNivel']);
                $nivelAnterior = $nivelAnterior["nivelCarrera"];

            }
        }
    
        return $nivelAnterior;
    }
    
    public function ArielsControl2($Usuario) {
        $AllMatriculas = $this->showAllMatriculas($Usuario);
        $idUltimoPeriodoActivo = $this->getIdPeriodoAcademicoActivo();
        $nivelActual = 0;
    
        if (count($AllMatriculas) > 0 && is_array(current($AllMatriculas))) {
            // Es bidimensional
            foreach ($AllMatriculas as $fila) {
                // Verificar si la clave existe antes de acceder
                if (isset($fila['periodo_academico_idPeriodo_Academico']) && $fila['periodo_academico_idPeriodo_Academico'] == $idUltimoPeriodoActivo) {
                    // Almacenar asignatura_nivel_idNivel de la tabla en la que encontró la coincidencia
                    $nivelActual = $this->showNivelCarrera($fila['asignatura_nivel_idNivel']);
                    $nivelActual = $nivelActual["nivelCarrera"];
                }
            }
        } else {
            // Es unidimensional
            // Verificar si la clave existe antes de acceder
            if (isset($AllMatriculas['periodo_academico_idPeriodo_Academico']) && $AllMatriculas['periodo_academico_idPeriodo_Academico'] == $idUltimoPeriodoActivo) {
                // Almacenar asignatura_nivel_idNivel de la tabla en la que encontró la coincidencia
                $nivelActual = $this->showNivelCarrera($AllMatriculas['asignatura_nivel_idNivel']);
                $nivelActual = $nivelActual["nivelCarrera"];
            }
        }
    
        return $nivelActual;
    }
    
    public function getAsignaturasMatriculadas($idEstudiante, $idPeriodoAcademico){
        try {
            $stmt = $this->connection->prepare("SELECT m.idMatricula, a.nombre_asignatura
                                                FROM matricula m
                                                INNER JOIN asignatura a ON m.asignatura_idAsignatura = a.idAsignatura
                                                WHERE m.estudiante_idEstudiante = :idEstudiante
                                                AND m.periodo_academico_idPeriodo_Academico = :idPeriodoAcademico");
            $stmt->bindValue(":idEstudiante", $idEstudiante);
            $stmt->bindValue(":idPeriodoAcademico", $idPeriodoAcademico);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }
    public function getIdMatricula($dataMatricula) {
        $stmt = $this->connection->prepare("SELECT idMatricula FROM matricula 
                                            WHERE periodo_academico_idPeriodo_Academico = :periodo_academico_id 
                                            AND estudiante_idEstudiante = :estudiante_id 
                                            AND asignatura_idAsignatura = :asignatura_id 
                                            AND asignatura_nivel_idNivel = :asignatura_nivel_id 
                                            AND asignatura_nivel_carrera_idCarrera = :asignatura_nivel_carrera_id");
        
        $stmt->bindValue(":periodo_academico_id", $dataMatricula["periodo_academico_idPeriodo_Academico"]);
        $stmt->bindValue(":estudiante_id", $dataMatricula["estudiante_idEstudiante"]);
        $stmt->bindValue(":asignatura_id", $dataMatricula["asignatura_idAsignatura"]);
        $stmt->bindValue(":asignatura_nivel_id", $dataMatricula["asignatura_nivel_idNivel"]);
        $stmt->bindValue(":asignatura_nivel_carrera_id", $dataMatricula["asignatura_nivel_carrera_idCarrera"]);
    
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            // Matrícula encontrada, retorna el idMatricula
            return $result["idMatricula"];
        } else {
            // Matrícula no encontrada, retorna false
            return false;
        }
    }
    



}   
//periodo_academico_idPeriodo_Academico