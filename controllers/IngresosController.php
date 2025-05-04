<?php
namespace App\controllers;

use App\models\entities\Ingreso;

require_once __DIR__ . '/../models/drivers/ConexDB.php';
require_once __DIR__ . '/../models/entities/Ingreso.php';

class ingresosController {
    public function getAllIngresos()
    {
        $model = new Ingreso();
        $Ingresos = $model->all();
        return $Ingresos;
    }

    public function saveNewIngreso($resquest)
    {
        $model = new Persona();
        $model->set('Id', $resquest['IDingreso']);
        $model->set('Mes', $resquest['Mesingreso']);
        $model->set('año', $resquest['Añoingreso']);
        $model->set('Valor', $resquest['Valoringreso']);
        $res = $model->save();
        return $res ? 'yes' : 'not';
    }

    public function updateIngreso($resquest){
        $model = new Ingreso();
        $model->set('Id', $resquest['IDingreso']);
        $model->set('Mes', $resquest['Mesingreso']);
        $model->set('año', $resquest['Añoingreso']);
        $model->set('Valor', $resquest['Valoringreso']);
        $res = $model->update();
        return $res ? 'yes' : 'not';
    }

    public function removeIngreso($id){
        $model = new Ingreso();
        $model->set('id', $id);
        if(empty($model->find())){
            return "empty";
        }
        $res =  $model->delete();
        return $res ? 'yes' : 'not';
    }

    public function getIngreso($id){
        $model = new Ingreso();
        $model->set('id', $id);
        return $model->find();
    }

}
