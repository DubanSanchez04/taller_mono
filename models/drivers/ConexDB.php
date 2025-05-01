<?php
namespace App\models\drivers;

use mysqli;

class ConexDB {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dataBase = 'proyecto_1_db';
    private $conex;

    public function __construct() {
        $this->conex = new mysqli($this->host, $this->user, $this->password, $this->dataBase);
        if ($this->conex->connect_error) {
            die("Error de conexión: " . $this->conex->connect_error);
        }
    }

    public function getConnection() {
        return $this->conex;
    }

    public function close() {
        $this->conex->close();
    }

    public function exeSQL($sql) {
        $result = $this->conex->query($sql);
        if (!$result) {
            throw new \Exception("Error en la consulta SQL: " . $this->conex->error);
        }
        return $result;
    }


}
