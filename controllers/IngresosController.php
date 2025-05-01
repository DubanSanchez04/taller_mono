<?php
namespace App\controllers;

require_once __DIR__ . '/../models/drivers/ConexDB.php';
require_once __DIR__ . '/../models/entities/Ingreso.php';

use App\models\drivers\ConexDB;
use App\models\entities\Ingreso;
use Exception;

class IngresosController {
    public function registrar($data) {
        try {
            $ingreso = new Ingreso();
            $ingreso->setMes($data['mes']);
            $ingreso->setAño($data['año']);
            $ingreso->setValor($data['valor']);

            if ($this->existeIngreso($data['mes'], $data['año'])) {
                throw new Exception("Ya existe un ingreso para este mes y año");
            }

            $ingreso->registrar();
            return ["success" => true, "message" => "Ingreso registrado"];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    private function existeIngreso($mes, $año) {
        $db = new ConexDB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT id FROM reports WHERE month = ? AND year = ?");
        if (!$stmt) {
            throw new \Exception("Error al preparar consulta: " . $conn->error);
        }

        $stmt->bind_param("si", $mes, $año);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;

        $stmt->close();
        $db->close();

        return $exists;
    }

    // En App\controllers\IngresosController.php

    public function listar() {
        try {
            $ingresos = Ingreso::obtenerTodos();
            return [
                'success' => true,
                'ingresos' => $ingresos
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function obtener($id) {
        try {
            $ingreso = Ingreso::obtenerPorId($id);
            return [
                'success' => true,
                'ingreso' => $ingreso
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
