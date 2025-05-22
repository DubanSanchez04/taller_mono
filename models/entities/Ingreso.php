<?php
namespace App\models\entities;
require_once __DIR__ . '/Model.php';
use App\models\drivers\ConexDB;
class Ingreso extends Model
{
    protected $id = null;
    protected $mes = null;
    protected $anio = null;
    protected $valor = null;

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
        ];
    }

    public function all()
    {
        $conexDb = new ConexDB();
        $sql = "SELECT DISTINCT r.id, r.month, r.year, i.value FROM `income` i RIGHT JOIN `reports` r on r.id = i.idReport";
        $res = $conexDb->exeSQL($sql);
        $ingresos = [];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $ingreso = new Ingreso();
                $ingreso->set('id', $row['id']);
                $ingreso->set('mes', $row['month']);
                $ingreso->set('anio', $row['year']);
                $ingreso->set('valor', $row['value']);
                array_push($ingresos, $ingreso);
            }

        }
        $conexDb->close();
        return $ingresos;
    }

    public function save()
    {
        $conexDb = new ConexDB();

        $newId = $this->getMaxReport($conexDb);
        $sqlInsertReport = "INSERT INTO reports (id, month, year) VALUES (?, ?, ?)";
        $stmt = $conexDb->prepare($sqlInsertReport);
        if ($stmt) {
            $stmt->bind_param("isi", $newId, $this->mes, $this->anio);
            $stmt->execute();
            $stmt->close();
            $newBillsId = $this->getMaxBills($conexDb);
            $sqlInsertBilling = "INSERT INTO income (id,value,idReport) VALUES (?,?,?)";
            $stmtBilling = $conexDb->prepare($sqlInsertBilling);
            if ($stmtBilling) {
                $category = 1;
                $stmtBilling->bind_param("iii", $newBillsId, $this->valor, $newId);
                $result = $stmtBilling->execute();
                $stmtBilling->close();
                $conexDb->close();
                return $result;
            } else {
                echo "Error en prepare: " . $conexDb->prepare($sqlInsertBilling)->error;
                $conexDb->close();
                return false;
            }
        } else {
            echo "Error en prepare: " . $conexDb->prepare($sqlInsertReport)->error;
            $conexDb->close();
            return false;
        }
    }

    public function getMaxReport($conexDb)
    {
        $maxIdQuery = "SELECT MAX(id) AS max_id FROM `reports`";
        $res = $conexDb->exeSQL($maxIdQuery);
        $row = $res->fetch_assoc();
        $newId = isset($row['max_id']) ? $row['max_id'] + 1 : 1;
        return $newId;
    }

    public function getMaxBills($conexDb)
    {
        $maxIdQuery = "SELECT MAX(id) AS max_id FROM `income`";
        $res = $conexDb->exeSQL($maxIdQuery);
        $row = $res->fetch_assoc();
        $newId = isset($row['max_id']) ? $row['max_id'] + 1 : 1;
        return $newId;
    }

    public function update()
    {
        $conexDb = new ConexDB();
        $sql = "UPDATE income SET value = ? WHERE idReport = ?";
        $stmt = $conexDb->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $this->valor, $this->id);
            $res = $stmt->execute();
            $stmt->close();
        } else {
            $res = false;
        }

        $conexDb->close();
        return $res;
    }

    public function delete()
    {
        $conexDb = new ConexDB();
        $sqlDeleteBills = "DELETE FROM income WHERE idReport = ?";
        $stmt = $conexDb->prepare($sqlDeleteBills);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->close();

        $sqlDeleteReports = "DELETE FROM reports WHERE id = ?";
        $stmtReport = $conexDb->prepare($sqlDeleteReports);
        $stmtReport->bind_param("i", $this->id);
        $stmtReport->execute();
        $stmtReport->close();

        $conexDb->close();
        return true;
    }

    public function find()
    {
        $conexDb = new ConexDB();
        $sql = "select * from reports where id=" . $this->id;
        $res = $conexDb->exeSQL($sql);
        $ingreso = null;
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $ingreso = new Ingreso();
                $ingreso->set('Id', $row['Id']);
                $ingreso->set('Mes', $row['Mes']);
                $ingreso->set('Ano', $row['Ano']);
                $ingreso->set('Valor', $row['Valor']);
                break;
            }
        }
        $conexDb->close();
        return $ingreso;
    }
    public function existsMesAnio($mes, $anio)
    {
        $conexDb = new ConexDB();
        $sql = "SELECT COUNT(*) AS count FROM reports WHERE month = ? AND year = ?";
        $stmt = $conexDb->prepare($sql);
        if (!$stmt) {
            $conexDb->close();
            return false;  // Por seguridad, evita insertar si falla
        }
        $stmt->bind_param("si", $mes, $anio);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conexDb->close();
        return $row['count'] > 0;
    }



}