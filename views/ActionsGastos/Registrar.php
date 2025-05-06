<?php
include '../../models/entities/Gasto.php';
include '../../controllers/GastosController.php';
include '../../models/drivers/ConexDB.php';

use App\controllers\GastosController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];
    $valor = $_POST['valor'];
    $idCategoria = $_POST['categoria'];
    
    $controlador = new GastosController();
    
    // Verificar si ya existe un reporte para este mes/año
    $idReporte = $controlador->getReporteByMesAnio($mes, $anio);
    
    if (!$idReporte) {
        // Si no existe, crear uno nuevo
        $idReporte = $controlador->createReporte($mes, $anio);
    }
    
    // Registrar el gasto
    if ($controlador->addGasto($valor, $idCategoria, $idReporte)) {
        $mensaje = "Gasto registrado correctamente.";
    } else {
        $mensaje = "Error al registrar el gasto.";
    }
    
    header("Location: ../gastos.php?mensaje=" . urlencode($mensaje));
    exit();
}