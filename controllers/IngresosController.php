<?php

namespace App\controllers;

use App\models\entities\Ingreso;

class ingresosController
{
    public function getAllIngresos()
    {
        $model = new Ingreso();
        $Ingresos = $model->all();
        return $Ingresos;
    }

    public function saveNewIngreso($request)
    {
        $model = new Ingreso();

        if ($model->existsMesAnio($request['mes'], $request['anio'])) {
            return 'duplicate';
        }

        $model->set('mes', $request['mes']);
        $model->set('anio', $request['anio']);
        $model->set('valor', !empty($request['valor']) ? $request['valor'] : 0);

        $res = $model->save();

        return $res ? 'yes' : 'not';
    }



    public function updateIngreso($request)
    {
        $model = new Ingreso();
        $model->set('id', $request['id']);
        $model->set('valor', $request['valor']);
        $res = $model->update();
        return $res ? 'yes' : 'not';
    }

    public function removeIngreso($id)
    {
        $model = new Ingreso();
        $model->set('id', $id);
        $res = $model->delete();
        return $res ? 'yes' : 'not';
    }


}
