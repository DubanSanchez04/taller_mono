<?php
namespace App\models\drivers;

use mysqli;

class ConexDB {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dataBase = 'proyecto_1_db';
    private $conex=null;

    public function __construct() {
        $this->conex = new mysqli(
            $this->host,
            $this->user, $this->password,
            $this->dataBase);

    }
    public function close() {
        $this->conex->close();
    }
    public function exeSQL($sql) {
        return $this->conex->query($sql);
    }

    public function prepare($sql) {
        return $this->conex->prepare($sql);
    }
}
