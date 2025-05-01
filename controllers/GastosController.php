<?php
namespace App\controllers;

use App\models\entities\Gasto;
use Exception;

class GastosController {
    public function registrar($data) {
        try {
            $gasto = new Gasto();
            $gasto->registrar();
            return ["success" => true, "message" => "Gasto registrado"];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}