<?php

namespace App\Controladores;

use Database\Connection;

class DocenteController{
    public function index() {}

    public function create() {}

    public function store($data) {
        $connection = Connection::getInstance()->get_database_instance();
        

        $stmt = $connection->prepare("INSERT INTO Estudiante (cedula, primer_nombre, primer_apellido,
         fecha_nacimiento, telefono_celular, correo, direccion_domicilio) VALUES(:cedula,
          :primer_nombre, :primer_apellido, :fecha_nacimiento, :telefono_celular, :correo, :direccion_domicilio);");

        $stmt->bindValue(":cedula", $data["cedula"]);
        $stmt->bindValue(":primer_nombre", $data["primer_nombre"]);
        $stmt->bindValue(":primer_apellido", $data["primer_apellido"]);
        $stmt->bindValue(":fecha_nacimiento", $data["fecha_nacimiento"]);
        $stmt->bindValue(":telefono_celular", $data["telefono_celular"]);
        $stmt->bindValue(":correo", $data["correo"]);
        $stmt->bindValue(":direccion_domicilio", $data["direccion_domicilio"]);

        $stmt->execute();


    }

    public function show() {}

    public function edit() {}

    public function update() {}

    public function destroy() {}

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


        /*
        '{$data['cedula']}',
        '{$data['primer_nombre']}',
        '{$data['primer_apellido']}',
        '{$data['fecha_nacimiento']}',
        '{$data['telefono_celular']}',
        '{$data['correo']}',
        '{$data['direccion_domicilio']}'
        */