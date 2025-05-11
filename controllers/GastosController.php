<?php

namespace App\controllers;

use App\models\entities\Gasto;

class GastosController
{
    public function getAllGastos()
{
    $db = new \App\models\drivers\ConexDB();
    $sql = "SELECT bills.id, bills.value, 
                   reports.month, reports.year, 
                   categories.name AS categoria, categories.percentage 
            FROM bills
            INNER JOIN reports ON bills.idReport = reports.id
            INNER JOIN categories ON bills.idCategory = categories.id";

    $res = $db->exeSQL($sql);

    $gastos = [];
    while ($row = $res->fetch_assoc()) {
        $gasto = new \App\models\entities\Gasto();
        $gasto->set('id', $row['id']);
        $gasto->set('valor', $row['value']);
        $gasto->set('mes', $row['month']);
        $gasto->set('anio', $row['year']);
        $gasto->set('categoria', $row['categoria']);
        $gasto->set('porcentaje', $row['percentage']);
        $gastos[] = $gasto;
    }

    return $gastos;
}

public function saveNewGasto($request)
{
    $model = new Gasto();
    $nombre = $request['categoria'];
    $porcentaje = floatval($request['porcentaje']);
    $db = new \App\models\drivers\ConexDB();

    if ($nombre === 'Otro' && !empty($request['nuevaCategoria'])) {
        $nombre = $request['nuevaCategoria'];
        $stmt = $db->prepare("INSERT INTO categories (name, percentage) VALUES (?, ?)");
        $stmt->bind_param("sd", $nombre, $porcentaje);
        $stmt->execute();
        $idCategoria = $stmt->insert_id;
    } else {
        // Verificar si la categoría ya existe
        $stmt = $db->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $idCategoria = $row['id'];

            // Actualizar porcentaje si se proporcionó
            $stmtUpdate = $db->prepare("UPDATE categories SET percentage = ? WHERE id = ?");
            $stmtUpdate->bind_param("di", $porcentaje, $idCategoria);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        } else {
            // Insertar si no existe (caso raro)
            $stmt = $db->prepare("INSERT INTO categories (name, percentage) VALUES (?, ?)");
            $stmt->bind_param("sd", $nombre, $porcentaje);
            $stmt->execute();
            $idCategoria = $stmt->insert_id;
        }
    }

    // Insertar o buscar report
    $stmt = $db->prepare("SELECT id FROM reports WHERE month = ? AND year = ?");
    $stmt->bind_param("si", $request['mes'], $request['anio']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $idReporte = $row['id'];
    } else {
        $stmt = $db->prepare("INSERT INTO reports (month, year) VALUES (?, ?)");
        $stmt->bind_param("si", $request['mes'], $request['anio']);
        $stmt->execute();
        $idReporte = $stmt->insert_id;
    }

    // Insertar gasto
    $valor = floatval($request['valor']);
    $stmt = $db->prepare("INSERT INTO bills (value, idCategory, idReport) VALUES (?, ?, ?)");
    $stmt->bind_param("dii", $valor, $idCategoria, $idReporte);
    $res = $stmt->execute();

    return $res ? 'yes' : 'not';
}



    
public function updateGasto($request)
{
    $model = new Gasto();
    $model->set('id', $request['id']);
    $model->set('valor', $request['valor']);
    
    // Si la categoría viene en la solicitud, actualizar también ese campo
    if (isset($request['idCategory'])) {
        $model->set('idCategory', $request['idCategory']);
    }
    
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
