<?php
include '../models/entities/Gasto.php';
include '../models/entities/Categoria.php';
include '../models/entities/Ingreso.php';
include '../controllers/GastosController.php';
include '../controllers/IngresosController.php';
include '../models/drivers/ConexDB.php';

use App\controllers\GastosController;
use App\controllers\IngresosController;

$mes = $_GET['mes'] ?? date('F');
$anio = $_GET['anio'] ?? date('Y');

$gastosController = new GastosController();
$ingresosController = new IngresosController();

$idReporte = $gastosController->getReporteByMesAnio($mes, $anio);

if ($idReporte) {
    $gastos = $gastosController->getGastosByReporte($idReporte);
    $totalGastos = array_sum(array_map(function($g) { return $g->get('valor'); }, $gastos));
 $totalGastos;
} else {
    $gastos = [];
    $ingresos = [];
    $totalGastos = 0;
    $totalIngresos = 0;
    $balance = 0;
}

$categorias = $gastosController->getCategorias();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Gastos - <?= ucfirst($mes) ?> <?= $anio ?></title>
    <link rel="stylesheet" href="css/styleregis.css">
    <style>
        .positivo { color: green; }
        .negativo { color: red; }
        .resumen { margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px; }
    </style>
</head>
<body>
<h1>Reporte de Gastos - <?= ucfirst($mes) ?> <?= $anio ?></h1>

<?php if (!empty($gastos)): ?>
    <h2>Detalle de Gastos</h2>
    <table border="1">
        <tr>
            <th>Categoría</th>
            <th>Porcentaje Asignado</th>
            <th>Valor Gastado</th>
            <th>Diferencia</th>
        </tr>
        <?php 
        $gastosPorCategoria = [];
        foreach ($gastos as $gasto) {
            $idCategoria = $gasto->get('idCategoria');
            if (!isset($gastosPorCategoria[$idCategoria])) {
                $gastosPorCategoria[$idCategoria] = [
                    'nombre' => $gasto->get('categoriaNombre'),
                    'porcentaje' => 0,
                    'valor' => 0
                ];
                
                // Buscar el porcentaje de la categoría
                foreach ($categorias as $categoria) {
                    if ($categoria->get('id') == $idCategoria) {
                        $gastosPorCategoria[$idCategoria]['porcentaje'] = $categoria->get('porcentaje');
                        break;
                    }
                }
            }
            $gastosPorCategoria[$idCategoria]['valor'] += $gasto->get('valor');
        }
        
        foreach ($gastosPorCategoria as $idCategoria => $datos): 
            $porcentajeReal = ($totalIngresos > 0) ? ($datos['valor'] / $totalIngresos) * 100 : 0;
            $diferencia = $datos['porcentaje'] - $porcentajeReal;
        ?>
            <tr>
                <td><?= htmlspecialchars($datos['nombre']) ?></td>
                <td><?= number_format($datos['porcentaje'], 2) ?>%</td>
                <td>$<?= number_format($datos['valor'], 2) ?></td>
                <td class="<?= ($diferencia >= 0) ? 'positivo' : 'negativo' ?>">
                    <?= number_format($diferencia, 2) ?>%
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <div class="resumen">
        <h2>Resumen Financiero</h2>
        <p><strong>Total Ingresos:</strong> $<?= number_format($totalIngresos, 2) ?></p>
        <p><strong>Total Gastos:</strong> $<?= number_format($totalGastos, 2) ?></p>
        <p><strong>Balance:</strong> 
            <span class="<?= ($balance >= 0) ? 'positivo' : 'negativo' ?>">
                $<?= number_format($balance, 2) ?>
            </span>
        </p>
    </div>
<?php else: ?>
    <p>No hay datos registrados para <?= ucfirst($mes) ?> <?= $anio ?>.</p>
<?php endif; ?>

<p><a href="gastos.php">Volver a Gastos</a></p>
</body>
</html>