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

    /**
     * @return null
     */
    public function getValor()
    {
        return $this->valor;
    }
    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @return null
     */
    public function getAnio()
    {
        return $this->anio;
    }


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
        $sql = "SELECT DISTINCT r.id, r.month, r.year, b.value FROM `bills` b INNER JOIN `reports` r on r.id = b.idReport";
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
        {
            $conexDb = new ConexDB();
            $sql = "insert into reports (id, month, year) values ";
            $sql .= "('" . $this->id . "','" . $this->mes . "'," . $this->anio . ")";
            $res = $conexDb->exeSQL($sql);
            $conexDb->close();
            return $res;
        }
    }

    public function update()
    {
        $conexDb = new ConexDB();
        $sql = "update reports set ";
        $sql .= "id='" . $this->id . "',";
        $sql .= "mes='" . $this->mes . "',";
        $sql .= "anio=" . $this->anio . " ";
        $sql .= " where id=" . $this->id;
        $res = $conexDb->exeSQL($sql);
        $conexDb->close();
        return $res;
    }

    public function delete()
    {
        $conexDb = new ConexDB();
        $sql = "delete from reports where id=" . $this->id;
        
        $res = $conexDb->exeSQL($sql);
        $conexDb->close();
        return $res;
    }

    public function find()
    {
        $conexDb = new ConexDB();
        $sql = "select * from reports where id=" . $this->id;
        $res = $conexDb->exeSQL($sql);
        $ingreso = null;
        if($res->num_rows>0){
            while($row = $res->fetch_assoc()){
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


    function printMesage($text)
    {
        echo "<script>console.log(JSON.stringify($text))</script>";
    }
}