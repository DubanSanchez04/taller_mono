<?php
namespace App\models\entities;

class Reporte {
    private $id;
    private $month;
    private $year;

    public function __construct($id, $month, $year) {
        $this->id = $id;
        $this->month = $month;
        $this->year = $year;
    }

    public function get($propiedad) {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        }
        return null;
    }

    public function set($propiedad, $valor) {
        if (property_exists($this, $propiedad)) {
            $this->$propiedad = $valor;
        }
    }
}