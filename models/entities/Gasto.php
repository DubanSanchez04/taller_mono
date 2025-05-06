<?php
namespace App\models\entities;

class Gasto {
    private $id;
    private $valor;
    private $idCategoria;
    private $idReporte;
    private $categoriaNombre; // Para mostrar el nombre en lugar del ID

    public function __construct($id, $valor, $idCategoria, $idReporte, $categoriaNombre = null) {
        $this->id = $id;
        $this->valor = $valor;
        $this->idCategoria = $idCategoria;
        $this->idReporte = $idReporte;
        $this->categoriaNombre = $categoriaNombre;
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