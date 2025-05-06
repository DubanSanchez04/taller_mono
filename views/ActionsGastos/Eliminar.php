<?php
include '../../controllers/GastosController.php';
include '../../models/drivers/ConexDB.php';

use App\controllers\GastosController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    $controlador = new GastosController();
    
    if ($controlador->deleteGasto($id)) {
        $mensaje = "Gasto eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar el gasto.";
    }
    
    header("Location: ../gastos.php?mensaje=" . urlencode($mensaje));
    exit();
}