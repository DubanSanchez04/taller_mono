<?php
namespace App\models\entities;

class Categoria {
    private $id;
    private $nombre;
    private $porcentaje;

    public function __construct($id, $nombre, $porcentaje) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->porcentaje = $porcentaje;
    }

    public function get($propiedad) {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        }
        return null;
    }
}