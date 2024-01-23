<?php

namespace App\Controladores;

use Database\Connection;

class ValidarCredenciales {

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

    public function validarCredencialesDocente($cedula, $contrasena) {
        $stmt = $this->connection->prepare("SELECT * FROM docentes WHERE cedula = :cedula AND passwordd = :contrasena");
        $stmt->bindValue(":cedula", $cedula);
        $stmt->bindValue(":contrasena", $contrasena);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function validarCredencialesEstudiante($cedula, $contrasena) {
        $stmt = $this->connection->prepare("SELECT * FROM estudiante WHERE cedula = :cedula AND passwordd = :contrasena");
        $stmt->bindValue(":cedula", $cedula);
        $stmt->bindValue(":contrasena", $contrasena);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
