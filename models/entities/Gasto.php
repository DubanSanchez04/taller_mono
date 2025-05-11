<?php

namespace App\models\entities;
require_once __DIR__ . '/ModelG.php';

use App\models\drivers\ConexDB;

class Gasto extends ModelG
{
    protected $id = null;
    protected $mes = null;
    protected $anio = null;
    protected $valor = null;
    protected $categoria = null;
    protected $porcentaje = null;
    protected $idCategory = null;
    protected $idReport = null;

    public static $mesesValidos = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
        'Junio', 'Julio', 'Agosto', 'Septiembre',
        'Octubre', 'Noviembre', 'Diciembre'
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'mes' => $this->mes,
            'anio' => $this->anio,
            'valor' => $this->valor,
            'categoria' => $this->categoria,
            'porcentaje' => $this->porcentaje
        ];
    }

    public function save()
    {
        $conexDb = new ConexDB();

        $idReport = $this->getMaxId('reports', $conexDb);
        $sqlInsertReport = "INSERT INTO reports (id, month, year) VALUES (?, ?, ?)";
        $stmt = $conexDb->prepare($sqlInsertReport);

        if (!$stmt) {
            echo "Error prepare (report): " . $conexDb->getConnection()->error;

            return false;
        }

        $stmt->bind_param("isi", $idReport, $this->mes, $this->anio);
        $stmt->execute();
        $stmt->close();

        $idBill = $this->getMaxId('bills', $conexDb);
        $idCategoria = $this->idCategory ?? 1;

        $sqlInsertBill = "INSERT INTO bills (id, value, idCategory, idReport) VALUES (?, ?, ?, ?)";
        $stmtBill = $conexDb->prepare($sqlInsertBill);

        if (!$stmtBill) {
            echo "Error prepare (report): " . $conexDb->getConnection()->error;
            return false;
        }

        $stmtBill->bind_param("diii", $this->valor, $idCategoria, $idReport, $idBill);
        $result = $stmtBill->execute();
        $stmtBill->close();
        $conexDb->close();

        return $result;
    }

    public function all()
    {
        $db = new ConexDB();

        $sql = "SELECT b.id, b.value, r.month AS mes, r.year AS anio, 
                       c.name AS categoria, c.percentage
                FROM bills b
                JOIN reports r ON b.idReport = r.id
                JOIN categories c ON b.idCategory = c.id";
        $result = $db->exeSQL($sql);
        $gastos = [];

        while ($row = $result->fetch_assoc()) {
            $gasto = new Gasto();
            $gasto->set('id', $row['id']);
            $gasto->set('valor', $row['value']);
            $gasto->set('mes', $row['mes']);
            $gasto->set('anio', $row['anio']);
            $gasto->set('categoria', $row['categoria']);
            $gasto->set('porcentaje', $row['percentage']);
            $gastos[] = $gasto;
        }

        return $gastos;
    }

    
public function update()
{
    $db = new ConexDB();
    
    // First, fetch the current record to get idCategory if it's not set
    if ($this->idCategory === null) {
        $sqlFind = "SELECT * FROM bills WHERE id = ?";
        $stmtFind = $db->prepare($sqlFind);
        $stmtFind->bind_param("i", $this->id);
        $stmtFind->execute();
        $result = $stmtFind->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $this->idCategory = $row['idCategory'];
        }
        $stmtFind->close();
    }
    
    // Now update with the retrieved or provided idCategory
    $sql = "UPDATE bills SET value = ?, idCategory = ? WHERE id = ?";
    $stmt = $db->prepare($sql);

    if (!$stmt) return false;

    $stmt->bind_param("dii", $this->valor, $this->idCategory, $this->id);
    $res = $stmt->execute();
    $stmt->close();
    $db->close();

    return $res;
}

    public function delete()
    {
        $db = new ConexDB();

        $sql = "DELETE FROM bills WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->close();

        // Opcional: también eliminar el report asociado si no tiene más gastos

        $db->close();
        return true;
    }

    public function find()
    {
        $db = new ConexDB();
        $sql = "SELECT * FROM bills WHERE id = " . intval($this->id);
        $res = $db->exeSQL($sql);

        $Gasto = null;
        if ($row = $res->fetch_assoc()) {
            $Gasto = new Gasto();
            $Gasto->set('id', $row['id']);
            $Gasto->set('valor', $row['value']);
            $Gasto->set('idCategory', $row['idCategory']);
            $Gasto->set('idReport', $row['idReport']);
        }

        $db->close();
        return $Gasto;
    }

    private function getMaxId($table, $conexDb)
    {
        $sql = "SELECT MAX(id) AS max_id FROM `$table`";
        $res = $conexDb->exeSQL($sql);
        $row = $res->fetch_assoc();
        return isset($row['max_id']) ? $row['max_id'] + 1 : 1;
    }
}