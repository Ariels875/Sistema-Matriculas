<?php

namespace App\Controladores;
use PDOException;
use Database\Connection;
use PDO;
class EstudianteController{

    private $connection;

    public function __construct(){
        $this->connection = Connection::getInstance()->get_database_instance();
    }

    // public function index() {
    //    $stmt = $this->connection->prepare("SELECT * FROM estudiante");
    //    $stmt->execute();
    //    $stmt->bindColumn("",)

    //    while($stmt->fetch())


        

    // }

    public function create() {}

    public function store($data) {

        $stmt = $this->connection->prepare("INSERT INTO estudiante (cedula, primer_nombre, primer_apellido,
         fecha_nacimiento, telefono_celular, correo, direccion_domicilio, passwordd) VALUES(:cedula,
          :primer_nombre, :primer_apellido, :fecha_nacimiento, :telefono_celular, :correo, :direccion_domicilio, :passwordd)");

        $stmt->bindValue(":cedula", $data["cedula"]);
        $stmt->bindValue(":primer_nombre", $data["primer_nombre"]);
        $stmt->bindValue(":primer_apellido", $data["primer_apellido"]);
        $stmt->bindValue(":fecha_nacimiento", $data["fecha_nacimiento"]);
        $stmt->bindValue(":telefono_celular", $data["telefono_celular"]);
        $stmt->bindValue(":correo", $data["correo"]);
        $stmt->bindValue(":direccion_domicilio", $data["direccion_domicilio"]);
        $stmt->bindValue(":passwordd", $data["passwordd"]);

        $stmt->execute();


    }

    public function show($cedula) {
        try{
            $stmt = $this->connection->prepare("SELECT * FROM estudiante WHERE cedula = :cedula");
            $stmt->bindValue(":cedula", $cedula);
            $stmt->execute();
            $info = $stmt->fetch();
            return $info;
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            return array();
        }
    }
    

    public function edit() {}

    public function update($cedula, $data) {
        $stmt = $this->connection->prepare("UPDATE estudiante SET cedula = :cedula, primer_nombre = :primer_nombre,
         primer_apellido = :primer_apellido, fecha_nacimiento = :fecha_nacimiento,
         telefono_celular = :telefono_celular, correo = :correo, direccion_domicilio = :direccion_domicilio,
         passwordd = :passwordd
         WHERE cedula = :cedula;");

        $stmt->bindValue(":cedula", $cedula);
        $stmt->bindValue(":primer_nombre", $data["primer_nombre"]);
        $stmt->bindValue(":primer_apellido", $data["primer_apellido"]);
        $stmt->bindValue(":fecha_nacimiento", $data["fecha_nacimiento"]);
        $stmt->bindValue(":telefono_celular", $data["telefono_celular"]);
        $stmt->bindValue(":correo", $data["correo"]);
        $stmt->bindValue(":direccion_domicilio", $data["direccion_domicilio"]);
        $stmt->bindValue(":passwordd", $data["passwordd"]);
        $stmt->bindValue(":idEstudiante", $data["idEstudiante"]);


        $stmt->execute();
    }

    public function destroyEstudiante($cedula) {
        $stmt = $this->connection->prepare("DELETE FROM estudiante WHERE cedula = :cedula");
        $stmt->execute([":cedula"=> $cedula]);

    }
    public function buscarEstudiante($busqueda) {
        $stmt = $this->connection->prepare("SELECT * FROM estudiante 
                                      WHERE cedula LIKE :busqueda OR 
                                            primer_nombre LIKE :busqueda OR 
                                            primer_apellido LIKE :busqueda");

        $busquedaParam = '%' . $busqueda . '%';
        $stmt->bindValue(":busqueda", $busquedaParam);
        $stmt->execute();

        $resultados = $stmt->fetchAll();

        return $resultados;
    }
    
    public function storePagos($data) {
        $stmt = $this->connection->prepare("INSERT INTO pagos (monto, fecha_pago, estadopago_idEstado_pago, matricula_idMatricula,
            matricula_periodo_academico_idPeriodo_Academico, matricula_estudiante_idEstudiante, matricula_asignatura_idAsignatura,
            matricula_asignatura_nivel_idNivel, matricula_asignatura_nivel_carrera_idCarrera) 
            VALUES (:monto, :fecha_pago, :estadopago_idEstado_pago, :matricula_idMatricula,
            :matricula_periodo_academico_idPeriodo_Academico, :matricula_estudiante_idEstudiante, :matricula_asignatura_idAsignatura,
            :matricula_asignatura_nivel_idNivel, :matricula_asignatura_nivel_carrera_idCarrera);");
    
        $stmt->bindValue(":monto", $data["monto"]);
        $stmt->bindValue(":fecha_pago", $data["fecha_pago"]);
        $stmt->bindValue(":estadopago_idEstado_pago", $data["estadopago_idEstado_pago"]);
        $stmt->bindValue(":matricula_idMatricula", $data["matricula_idMatricula"]);
        $stmt->bindValue(":matricula_periodo_academico_idPeriodo_Academico", $data["matricula_periodo_academico_idPeriodo_Academico"]);
        $stmt->bindValue(":matricula_estudiante_idEstudiante", $data["matricula_estudiante_idEstudiante"]);
        $stmt->bindValue(":matricula_asignatura_idAsignatura", $data["matricula_asignatura_idAsignatura"]);
        $stmt->bindValue(":matricula_asignatura_nivel_idNivel", $data["matricula_asignatura_nivel_idNivel"]);
        $stmt->bindValue(":matricula_asignatura_nivel_carrera_idCarrera", $data["matricula_asignatura_nivel_carrera_idCarrera"]);
    
        $stmt->execute();
    }
    public function verificarExistenciaPago($data) {
        $stmt = $this->connection->prepare("SELECT idPagos FROM pagos 
                                            WHERE monto = :monto 
                                            AND fecha_pago = :fecha_pago 
                                            AND estadopago_idEstado_pago = :estadopago_idEstado_pago 
                                            AND matricula_idMatricula = :matricula_idMatricula 
                                            AND matricula_periodo_academico_idPeriodo_Academico = :matricula_periodo_academico_idPeriodo_Academico 
                                            AND matricula_estudiante_idEstudiante = :matricula_estudiante_idEstudiante 
                                            AND matricula_asignatura_idAsignatura = :matricula_asignatura_idAsignatura 
                                            AND matricula_asignatura_nivel_idNivel = :matricula_asignatura_nivel_idNivel 
                                            AND matricula_asignatura_nivel_carrera_idCarrera = :matricula_asignatura_nivel_carrera_idCarrera");
        
        $stmt->bindValue(":monto", $data["monto"]);
        $stmt->bindValue(":fecha_pago", $data["fecha_pago"]);
        $stmt->bindValue(":estadopago_idEstado_pago", $data["estadopago_idEstado_pago"]);
        $stmt->bindValue(":matricula_idMatricula", $data["matricula_idMatricula"]);
        $stmt->bindValue(":matricula_periodo_academico_idPeriodo_Academico", $data["matricula_periodo_academico_idPeriodo_Academico"]);
        $stmt->bindValue(":matricula_estudiante_idEstudiante", $data["matricula_estudiante_idEstudiante"]);
        $stmt->bindValue(":matricula_asignatura_idAsignatura", $data["matricula_asignatura_idAsignatura"]);
        $stmt->bindValue(":matricula_asignatura_nivel_idNivel", $data["matricula_asignatura_nivel_idNivel"]);
        $stmt->bindValue(":matricula_asignatura_nivel_carrera_idCarrera", $data["matricula_asignatura_nivel_carrera_idCarrera"]);
    
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            // Pago encontrado, retorna true
            return true;
        } else {
            // Pago no encontrado, retorna false
            return false;
        }
    }
    public function obtenerPagosPorAlumno($alumno_id) {
        try {
            $stmt = $this->connection->prepare("SELECT * 
                                               FROM pagos p
                                               JOIN matricula m ON p.matricula_idMatricula = m.idMatricula
                                               WHERE m.estudiante_idEstudiante = :alumno_id");
            
            $stmt->bindValue(":alumno_id", $alumno_id);
            $stmt->execute();
            $resultados = $stmt->fetchAll();
    
            if (!empty($resultados)) {
                echo '<h2>Resultados de la búsqueda:</h2>';
                echo '<table>';
                echo '<tr><th>ID de Pago</th><th>Monto</th><th>Fecha de Pago</th><th>ID de Matrícula</th></tr>';
                
                foreach ($resultados as $pago) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($pago['idPagos']) . '</td>';
                    echo '<td>' . htmlspecialchars($pago['monto']) . '</td>';
                    echo '<td>' . htmlspecialchars($pago['fecha_pago']) . '</td>';
                    echo '<td>' . htmlspecialchars($pago['matricula_idMatricula']) . '</td>';
                    echo '</tr>';
                }
    
                echo '</table>';
            } else {
                echo '<p>No se encontraron resultados.</p>';
            }
    
            return $resultados;
        } catch (PDOException $e) {
            // Manejar errores de conexión o consulta
            echo "Error al obtener los pagos: " . $e->getMessage();
            return false;
        }
    }
    public function obtenerInformacionPago($pago_id) {
        try {
            $stmt = $this->connection->prepare("SELECT * 
                                               FROM pagos p
                                               JOIN matricula m ON p.matricula_idMatricula = m.idMatricula
                                               WHERE p.idPagos = :pago_id");
            
            $stmt->bindValue(":pago_id", $pago_id);
            $stmt->execute();
            $informacionPago = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($informacionPago) {
                echo '<h2>Información del Pago:</h2>';
                echo '<table>';
                echo '<tr><th>ID de Pago</th><th>Monto</th><th>Fecha de Pago</th><th>ID de Matrícula</th></tr>';
                
                echo '<tr>';
                echo '<td>' . htmlspecialchars($informacionPago['idPagos']) . '</td>';
                echo '<td>' . htmlspecialchars($informacionPago['monto']) . '</td>';
                echo '<td>' . htmlspecialchars($informacionPago['fecha_pago']) . '</td>';
                echo '<td>' . htmlspecialchars($informacionPago['matricula_idMatricula']) . '</td>';
                echo '</tr>';
    
                echo '</table>';
            } else {
                echo '<p>No se encontró información para el ID de pago especificado.</p>';
            }
    
            return $informacionPago;
        } catch (PDOException $e) {
            // Manejar errores de conexión o consulta
            echo "Error al obtener la información del pago: " . $e->getMessage();
            return false;
        }
    }
    public function actualizarPago($pago_id,$estadopago_idEstado_pago) {
        try {
            $stmt = $this->connection->prepare("UPDATE pagos 
                                               SET estadopago_idEstado_pago = :estadopago_idEstado_pago
                                               WHERE idPagos = :pago_id");
    
            $stmt->bindValue(":pago_id", $pago_id);
            $stmt->bindValue(":estadopago_idEstado_pago", $estadopago_idEstado_pago);
            
            $stmt->execute();
    
            echo "Pago actualizado correctamente.";
    
            return true;
        } catch (PDOException $e) {
            // Manejar errores de conexión o consulta
            echo "Error al actualizar el pago: " . $e->getMessage();
            return false;
        }
    }
}
