<?php

namespace Database;

class Connection{
    private static $instance;
    private $connection;

    private function __construct(){
        $this->make_connection();
    }

    public static function getInstance(){
        if (!self::$instance instanceof self)
            self::$instance = new self();

        return self::$instance;

    }

    public function get_database_instance(){
        return $this->connection;

    }

    private function make_connection(){
        $database = "matriculas";
        $host = "localhost";
        $usuario = "root";
        $clave = "toor";

        $conexion = new \PDO("mysql:host=$host;
        dbname=$database", $usuario, $clave);

        // if ($mysqli->connect_errno) {
        //     die("Error de conexión: {$mysqli->connect_error}");
        // }
            
        $setnames = $conexion-> prepare("SET NAMES 'utf8'");
        $setnames->execute();
        
        $this->connection = $conexion;
    }

}

