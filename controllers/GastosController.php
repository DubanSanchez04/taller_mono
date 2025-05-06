<?php
namespace App\controllers;

use App\models\drivers\ConexDB;
use App\models\entities\Gasto;
use App\models\entities\Categoria;
use App\models\entities\Reporte;


class GastosController {
    private $conex;

    public function __construct() {
        $this->conex = new ConexDB();
    }

    public function getAllGastos() {
        $sql = "SELECT b.id, b.value, b.idCategory, b.idReport, c.nombre as categoriaNombre 
                FROM bills b 
                JOIN categorias c ON b.idCategory = c.id";
        $result = $this->conex->exeSQL($sql);
        
        $gastos = [];
        while ($row = $result->fetch_assoc()) {
            $gastos[] = new Gasto(
                $row['id'],
                $row['value'],
                $row['idCategory'],
                $row['idReport'],
                $row['categoriaNombre']
            );
        }
        return $gastos;
    }

    public function getGastosByReporte($idReporte) {
        $sql = "SELECT b.id, b.value, b.idCategory, b.idReport, c.name as categoriaNombre 
                FROM bills b 
                JOIN categorias c ON b.idCategory = c.id
                WHERE b.idReport = ?";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("i", $idReporte);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $gastos = [];
        while ($row = $result->fetch_assoc()) {
            $gastos[] = new Gasto(
                $row['id'],
                $row['value'],
                $row['idCategory'],
                $row['idReport'],
                $row['categoriaNombre']
            );
        }
        return $gastos;
    }

    public function getCategorias() {
        $sql = "SELECT * FROM categorias";
        $result = $this->conex->exeSQL($sql);
        
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = new Categoria(
                $row['id'],
                $row['name'],
                $row['percentage']
            );
        }
        return $categorias;
    }

    public function addGasto($valor, $idCategoria, $idReporte) {
        $sql = "INSERT INTO bills (value, idCategory, idReport) VALUES (?, ?, ?)";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("dii", $valor, $idCategoria, $idReporte);
        return $stmt->execute();
    }

    public function updateGasto($id, $valor, $idCategoria, $idReporte) {
        $sql = "UPDATE bills SET value = ?, idCategory = ?, idReport = ? WHERE id = ?";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("diii", $valor, $idCategoria, $idReporte, $id);
        return $stmt->execute();
    }

    public function deleteGasto($id) {
        $sql = "DELETE FROM bills WHERE id = ?";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getReportes() {
        $sql = "SELECT * FROM reports ORDER BY year DESC, FIELD(month, 
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') DESC";
        $result = $this->conex->exeSQL($sql);
        
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = new Reporte($row['id'], $row['month'], $row['year']);
        }
        return $reportes;
    }

    public function getReporteByMesAnio($mes, $anio) {
        $sql = "SELECT id FROM reports WHERE month = ? AND year = ?";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("si", $mes, $anio);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
        return null;
    }

    public function createReporte($mes, $anio) {
        $sql = "INSERT INTO reports (month, year) VALUES (?, ?)";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("si", $mes, $anio);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function __destruct() {
        $this->conex->close();
    }
}