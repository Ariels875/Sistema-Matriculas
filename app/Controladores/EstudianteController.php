<?php

namespace App\Controladores;

use Database\Connection;

class EstudianteController{

    private $connection;

    public function __construct(){
        $this->connection = Connection::getInstance()->get_database_instance();
    }

    public function index() {
       $stmt = $this->connection->prepare("SELECT * FROM estudiante");

       $stmt->execute();

       $results = $stmt->fetchAll();

        var_dump($results);
        foreach($results as $result)
            echo "tu user es ".$result["cedula"]." tu password es".$result["passwordd"];

    }

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

    public function show() {}

    public function edit() {}

    public function update() {}

    public function destroy() {}

}
