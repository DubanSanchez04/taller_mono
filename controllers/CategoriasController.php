<?php

namespace App\controllers;

use App\models\entities\Categoria;

class CategoriasController
{
    public function getAllCategorias()
    {
        $model = new Categoria();
        return $model->all();
    }

    public function saveNewCategoria($request)
    {

        $porcentaje = floatval($request['percentage']);
        if ($porcentaje <= 0 || $porcentaje > 100) {
            return 'invalid_percentage';
        }

        $model = new Categoria();
        $model->set('name', $request['name']);
        $model->set('percentage', $porcentaje);
        
        $res = $model->save();
        return $res ? 'yes' : 'not';
    }

    public function updateCategoria($request)
{
    $model = new Categoria();
    $model->set('id', $request['id']);


    if ($model->isLinkedToExpenses()) {
        return 'linked_to_expenses';
    }

    $model->set('name', $request['name']);



    $res = $model->update(['name']);
    return $res ? 'yes' : 'not';
}

    public function removeCategoria($id)
    {
        $model = new Categoria();
        $model->set('id', $id);
        
        if ($model->isLinkedToExpenses()) {
            return 'linked_to_expenses';
        }
        
        $res = $model->delete();
        return $res ? 'yes' : 'not';
    }

    public function findCategoria($id)
    {
        $model = new Categoria();
        $model->set('id', $id);
        return $model->find();
    }
}