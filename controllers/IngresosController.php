<?php
namespace App\controllers;
use App\models\entities\Ingreso;
class ingresosController {
    public function getAllIngresos()
    {
        $model = new Ingreso();
        $Ingresos = $model->all();
        return $Ingresos;
    }

    public function saveNewIngreso($resquest)
    {
        $model = new Ingreso();

        $model->set('Id', isset($resquest['id']) ? $resquest['id'] : null);
        $model->set('Mes', isset($resquest['mes']) ? $resquest['mes'] : null);
        $model->set('Anio', isset($resquest['anio']) ? $resquest['anio'] : null);
        $model->set('Valor', isset($resquest['valor']) ? $resquest['valor'] : null);

        $res = $model->save();

        return $res ? 'yes' : 'not';
    }


    public function updateIngreso($resquest){
        $model = new Ingreso();
        $model->set('Id', $resquest['IDingreso']);
        $model->set('Mes', $resquest['Mesingreso']);
        $model->set('anio', $resquest['año']);
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
