<?php
namespace App\models\entities;

use App\models\drivers\ConexDB;
use Exception;

class Ingreso  extends Model{
    protected $id=null;
    protected $Mes=null;
    protected $Año=null;
    protected $Valor=null;

    public static $mesesValidos = [
        'Enero' , 'Febrero', 'Marzo', 'Abril', 'Mayo',
        'Junio', 'Julio', 'Agosto', 'Septiembre',
        'Octubre', 'Noviembre', 'Diciembre'
    ];

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

    public function all()
    {
        $conexDb = new ConexDB();
        $sql = "select * from reports";
        $res = $conexDb->exeSQL($sql);
        $ingreso = [];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $ingreso = new reports();
                $ingreso->set('Id', $row['Id']);
                $ingreso->set('Mes', $row['Mes']);
                $ingreso->set('Año', $row['Año']);
                $ingreso->set('Valor', $row['Valor']);
                array_push($ingreso, $ingreso);
            }
        }
        $conexDb->close();
        return $ingreso;
    }

    public function save()
    {
        $conexDb = new ConexDB();
        $sql = "insert into reports (id, month, year) values ";
        $sql .= "('" . $this->Id . "','" . $this->Mes . "'," . $this->Año . ")";
        $res = $conexDb->exeSQL($sql);
        $conexDb->close();
        return $res;
    }

    public function update()
    {
        $conexDb = new ConexDB();
        $sql = "update reports set ";
        $sql .= "Id='" . $this->Id . "',";
        $sql .= "Mes='" . $this->Mes . "',";
        $sql .= "Año=" . $this->Año . " ";
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

    public function find(){
        $conexDb = new ConexDB();
        $sql = "select * from reports where id=" . $this->id;
        $res = $conexDb->exeSQL($sql);
        $ingreso = null;
        if($res->num_rows>0){
            while($row = $res->fetch_assoc()){
                $ingreso = new Ingreso();
                $ingreso->set('Id', $row['Id']);
                $ingreso->set('Mes', $row['Mes']);
                $ingreso->set('Año', $row['Año']);
                $ingreso->set('Valor', $row['Valor']);
                break;
            }
        }
        $conexDb->close();
        return $ingreso;

    }
}