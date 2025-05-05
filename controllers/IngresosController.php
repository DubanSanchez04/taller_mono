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
        $model->set('mes', $request['mes']);
        $model->set('anio', $request['anio']);
        $model->set('valor', $request['valor'] = !null ? $request['valor'] : 0);
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
        if (empty($model->find())) {
            return "empty";
        }
        $res = $model->delete();
        return $res ? 'yes' : 'not';
    }

    public function getIngreso($id)
    {
        $model = new Ingreso();
        $model->set('id', $id);
        return $model->find();
    }

    function printMessage($data)
    {
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        if ($json === false) {
            echo "<script>console.error('Error al codificar en JSON');</script>";
        } else {
            echo "<script>console.log($json);</script>";
        }
    }

}
