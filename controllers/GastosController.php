<?php

namespace App\controllers;

use App\models\entities\Gasto;

class GastosController
{
    public function getAllGastos()
    {
        $model = new Gasto();
        $gastos = $model->all();
        return $gastos;
    }

    public function saveNewGasto($request)
    {
        $model = new Gasto();
        $model->set('mes', $request['mes']);
        $model->set('anio', $request['anio']);
        $model->set('valor', $request['valor'] = !null ? $request['valor'] : 0);
        $res = $model->save();

        return $res ? 'yes' : 'not';
    }

    public function updateGasto($request)
    {
        $model = new Gasto();
        $model->set('id', $request['id']);
        $model->set('valor', $request['valor']);
        $res = $model->update();
        return $res ? 'yes' : 'not';
    }

    public function removeGasto($id)
    {
        $model = new Gasto();
        $model->set('id', $id);
        $res = $model->delete();
        return $res ? 'yes' : 'not';
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
