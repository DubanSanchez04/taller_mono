<?php
namespace App\models\entities;

use App\models\drivers\ConexDB;
use Exception;

class Gasto {
    private $id;
    private $categoria;
    private $mes;
    private $año;
    private $valor;

    // ... setters con validaciones similares ...

    public function registrar() {
        $db = new ConexDB();
        $stmt = $db->prepare("INSERT INTO gastos (categoria, mes, año, valor) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssid", $this->categoria, $this->mes, $this->año, $this->valor);
        $stmt->execute();
        $db->close();
    }
}