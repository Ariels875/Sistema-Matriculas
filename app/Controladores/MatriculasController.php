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
        // Función para obtener el periodo académico activo
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
    
    public function showNivelCarrera($idnivel) {
        $stmt = $this->connection->prepare("SELECT nivelCarrera FROM nivel WHERE idNivel = :nivel_idNivel");
        $stmt->bindValue(":nivel_idNivel", $idnivel);
        $stmt->execute();
    
        return $stmt->fetch();
    }
    public function ArielsControl2($Usuario) {
        $AllMatriculas = $this->showAllMatriculas($Usuario);
        $idUltimoPeriodoActivo = $this->getIdPeriodoAcademicoActivo();
        $nivelActual = 0;
    
        if (count($AllMatriculas) > 0 && is_array(current($AllMatriculas))) {
            // Es bidimensional
            foreach ($AllMatriculas as $fila) {
                // Comparar el valor periodo_academico_idPeriodo_Academico con $idUltimoPeriodoActivo
                if ($fila['periodo_academico_idPeriodo_Academico'] == $idUltimoPeriodoActivo) {
                    // Almacenar asignatura_nivel_idNivel de la tabla en la que encontró la coincidencia
                    $nivelActual = $this->showNivelCarrera($fila['asignatura_nivel_idNivel']);
                    $nivelActual = $nivelActual["nivelCarrera"];

                }
            }
        } else {
            // Es unidimensional
            // Comparar el valor periodo_academico_idPeriodo_Academico con $idUltimoPeriodoActivo
            if ($AllMatriculas['periodo_academico_idPeriodo_Academico'] == $idUltimoPeriodoActivo) {
                // Almacenar asignatura_nivel_idNivel de la tabla en la que encontró la coincidencia
                $nivelActual = $this->showNivelCarrera($AllMatriculas['asignatura_nivel_idNivel']);
                $nivelActual = $nivelActual["nivelCarrera"];

            }
        }
    
        return $nivelActual;
    }
    


}   
//periodo_academico_idPeriodo_Academico