<?php
namespace App\models\entities;

use App\models\drivers\ConexDB;
use Exception;

class Ingreso {

    public static $mesesValidos = [
        'Enero' , 'Febrero', 'Marzo', 'Abril', 'Mayo',
        'Junio', 'Julio', 'Agosto', 'Septiembre',
        'Octubre', 'Noviembre', 'Diciembre'
    ];

    private $mes;
    private $año;
    private $valor;

    public function setMes($mes) {
        if (!in_array($mes, self::$mesesValidos)) {
            throw new Exception("Mes no válido");
        }
        $this->mes = $mes;
    }

    public function setAño($año) {
        if ($año < 2000 || $año > 2100) {
            throw new Exception("Año no válido");
        }
        $this->año = $año;
    }

    public function setValor($valor) {
        if ($valor <= 0) {
            throw new Exception("El ingreso debe ser mayor a cero");
        }
        $this->valor = $valor;
    }

    public function registrar() {
        $db = new ConexDB();
        $conn = $db->getConnection();

        // Primero buscar el id del report correspondiente
        $stmtReport = $conn->prepare("SELECT id FROM reports WHERE month = ? AND year = ?");
        $stmtReport->bind_param("si", $this->mes, $this->año);
        $stmtReport->execute();
        $result = $stmtReport->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("No existe un reporte con ese mes y año");
        }

        $row = $result->fetch_assoc();
        $idReport = $row['id'];
        $stmtReport->close();

        // Insertar el ingreso
        $stmt = $conn->prepare("INSERT INTO income (idReport, value) VALUES (?, ?)");
        $stmt->bind_param("id", $idReport, $this->valor);
        $stmt->execute();
        $stmt->close();

        $db->close();
    }

    public static function buscarPorMesAño($mes, $año) {
        $db = new ConexDB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT * FROM reports WHERE month = ? AND year = ?");
        $stmt->bind_param("si", $mes, $año);
        $stmt->execute();
        $result = $stmt->get_result();
        $report = $result->fetch_assoc();
        $stmt->close();

        if (!$report) {
            $db->close();
            return null;
        }

        $idReport = $report['id'];
        $stmt2 = $conn->prepare("SELECT * FROM income WHERE idReport = ?");
        $stmt2->bind_param("i", $idReport);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $ingreso = $result2->fetch_assoc();
        $stmt2->close();
        $db->close();

        return $ingreso;
    }
    public function actualizarValor($id, $nuevoValor) {
        if ($nuevoValor <= 0) {
            throw new Exception("El ingreso debe ser mayor a cero");
        }

        $db = new ConexDB();
        $sql = "UPDATE income SET value = ? WHERE id = ?";
        $stmt = $db->getConexion()->prepare($sql);
        $stmt->bind_param("di", $nuevoValor, $id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("No se encontró el ingreso con ese ID");
        }

        $db->close();
    }

    public static function obtenerTodos() {
        $db = new ConexDB();
        $conn = $db->getConnection();

        $query = "SELECT i.id, r.month as mes, r.year as año, i.value as valor 
              FROM income i 
              JOIN reports r ON i.idReport = r.id 
              ORDER BY r.year DESC, FIELD(r.month, 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') DESC";

        $result = $conn->query($query);
        $ingresos = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ingresos[] = $row;
            }
        }

        $db->close();
        return $ingresos;
    }

    public static function obtenerPorId($id) {
        $db = new ConexDB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT i.id, r.month as mes, r.year as año, i.value as valor 
                           FROM income i 
                           JOIN reports r ON i.idReport = r.id 
                           WHERE i.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ingreso = $result->fetch_assoc();

        $stmt->close();
        $db->close();

        if (!$ingreso) {
            throw new Exception("Ingreso no encontrado");
        }

        return $ingreso;
    }
}
