<?php

namespace App\Controladores;

use Database\Connection;

class DocenteController{
    
    private $connection;

    public function __construct(){
        $this->connection = Connection::getInstance()->get_database_instance();
    }
    
    public function index() {

        $stmt = $this->connection->prepare("SELECT * FROM docentes");
        $stmt->execute();
        $docentes = $stmt->fetchAll();
        return $docentes;
    }

    public function create() {
        // Puedes implementar esta función si necesitas cargar una vista para crear un docente
    }

    public function store($data) {

        $stmt = $this->connection->prepare("INSERT INTO docentes (cedula, primer_nombre, primer_apellido,
         fecha_nacimiento, telefono_celular, correo, direccion_domicilio) VALUES(:cedula,
          :primer_nombre, :primer_apellido, :fecha_nacimiento, :telefono_celular, :correo, :direccion_domicilio);");

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

    public function show($usuario) {
        $stmt = $this->connection->prepare("SELECT * FROM docentes WHERE cedula = :usuario");
        $stmt->bindValue(":usuario", $usuario);
        $stmt->execute();
        $infodocente = $stmt->fetch();
        return $infodocente;
    }

    public function edit($id) {
        $docente = $this->show($id);
        // Puedes implementar esta función si necesitas cargar una vista para editar un docente
        return $docente;
    }

    public function update($docenteinfo, $data) {
        $stmt = $this->connection->prepare("UPDATE docentes SET cedula = :cedula, primer_nombre = :primer_nombre,
         primer_apellido = :primer_apellido, fecha_nacimiento = :fecha_nacimiento,
         telefono_celular = :telefono_celular, correo = :correo, direccion_domicilio = :direccion_domicilio,
         passwordd = :passwordd
         WHERE cedula = :cedula;");

        $stmt->bindValue(":cedula", $docenteinfo);
        $stmt->bindValue(":primer_nombre", $data["primer_nombre"]);
        $stmt->bindValue(":primer_apellido", $data["primer_apellido"]);
        $stmt->bindValue(":fecha_nacimiento", $data["fecha_nacimiento"]);
        $stmt->bindValue(":telefono_celular", $data["telefono_celular"]);
        $stmt->bindValue(":correo", $data["correo"]);
        $stmt->bindValue(":direccion_domicilio", $data["direccion_domicilio"]);
        $stmt->bindValue(":passwordd", $data["passwordd"]);
        $stmt->bindValue(":idDocentes", $data["idDocentes"]);


        $stmt->execute();
    }

    public function destroy($id) {
        $this->connection->beginTransaction();
        $stmt = $this->connection->prepare("DELETE FROM docentes WHERE idDocentes = :id");
        $stmt->bindValue(":cedula", $id);
        $stmt->execute();

        $confirma = readLine("Seguro que deseas eliminar este registro?");
        if ($confirma == "no")
        $this->connection->rollBack();
        else
            $this->connection->commit();
    }
    // A partir de aqui estan las funciones usadas en modificar_estudiante
    public function destroyEstudiante($cedula) {
        $stmt = $this->connection->prepare("DELETE FROM estudiante WHERE cedula = :cedula");
        $stmt->execute([":cedula"=> $cedula]);

    }

    public function buscarDocente($busqueda) {
        $connection = Connection::getInstance()->get_database_instance();

        // Consulta SQL para buscar docentes por cédula o nombre
        $stmt = $connection->prepare("SELECT * FROM docentes 
                                      WHERE cedula LIKE :busqueda OR 
                                            primer_nombre LIKE :busqueda OR 
                                            primer_apellido LIKE :busqueda");

        $busquedaParam = '%' . $busqueda . '%';
        $stmt->bindValue(":busqueda", $busquedaParam);
        $stmt->execute();

        $resultados = $stmt->fetchAll();

        return $resultados;
    }


}


/*los controladores tienen normalmente 7 metodos y se usan
para controlar cosas como el ingreso de usuarios o salidas, etc

index muestra una lista de los recursos

create muestra la forma para crear un nuevo recurso

store almacena recursos recien creados en almacenamiento

show muestra un recurso

edit muestra la forma para editar un recurso especifico

update actualiza un recurso del almacenamiento

destroy quita un recurso especifico del almacenamiento
*/