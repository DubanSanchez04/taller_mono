<?php
include '../models/entities/Gasto.php';
include '../models/entities/Categoria.php';
include '../controllers/GastosController.php';
include '../models/drivers/ConexDB.php';
include '../models/entities/Reporte.php';

use App\controllers\GastosController;

$controlador = new GastosController();
$gastos = $controlador->getAllGastos();
$categorias = $controlador->getCategorias();
$reportes = $controlador->getReportes();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrar Gasto</title>
    <link rel="stylesheet" href="css/styleregis.css">
</head>
<body>
<h1>Registrar Gasto</h1>

<?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>

<form action="ActionsGastos/Registrar.php" method="post">
    <label>Mes:</label>
    <select name="mes" required>
        <option value="enero">Enero</option>
        <option value="febrero">Febrero</option>
        <option value="marzo">Marzo</option>
        <option value="abril">Abril</option>
        <option value="mayo">Mayo</option>
        <option value="junio">Junio</option>
        <option value="julio">Julio</option>
        <option value="agosto">Agosto</option>
        <option value="septiembre">Septiembre</option>
        <option value="octubre">Octubre</option>
        <option value="noviembre">Noviembre</option>
        <option value="diciembre">Diciembre</option>
    </select>
    <br>
    
    <label for="anio">Año:</label>
    <input type="number" name="anio" value="<?= date('Y') ?>" min="1900" max="2050" required><br><br>
    
    <label for="categoria">Categoría:</label>
    <select name="categoria" required>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?= htmlspecialchars($categoria->get('id')) ?>">
                <?= htmlspecialchars($categoria->get('nombre')) ?> (<?= $categoria->get('porcentaje') ?>%)
            </option>
        <?php endforeach; ?>
    </select><br><br>
    
    <label for="valor">Valor:</label>
    <input type="number" name="valor" step="0.01" required><br><br>
    
    <button type="submit" name="submit">Guardar</button>
</form>

<h2>Lista de Gastos</h2>

<?php if (!empty($gastos)): ?>
    <table border="1">
        <tr>
            <th>Mes</th>
            <th>Año</th>
            <th>Categoría</th>
            <th>Valor</th>
            <th>Acciones</th>
        </tr>
        <?php 
        // Agrupar gastos por reporte para mostrar mes y año una sola vez
        $gastosAgrupados = [];
        foreach ($gastos as $gasto) {
            $idReporte = $gasto->get('idReporte');
            if (!isset($gastosAgrupados[$idReporte])) {
                $gastosAgrupados[$idReporte] = [
                    'mes' => '',
                    'anio' => '',
                    'gastos' => []
                ];
                
                // Buscar el reporte correspondiente
                foreach ($reportes as $reporte) {
                    if ($reporte->get('id') == $idReporte) {
                        $gastosAgrupados[$idReporte]['mes'] = $reporte->get('month');
                        $gastosAgrupados[$idReporte]['anio'] = $reporte->get('year');
                        break;
                    }
                }
            }
            $gastosAgrupados[$idReporte]['gastos'][] = $gasto;
        }
        
        foreach ($gastosAgrupados as $idReporte => $grupo): 
            $firstRow = true;
            foreach ($grupo['gastos'] as $gasto): 
        ?>
            <tr>
                <?php if ($firstRow): ?>
                    <td rowspan="<?= count($grupo['gastos']) ?>"><?= htmlspecialchars($grupo['mes']) ?></td>
                    <td rowspan="<?= count($grupo['gastos']) ?>"><?= htmlspecialchars($grupo['anio']) ?></td>
                    <?php $firstRow = false; ?>
                <?php endif; ?>
                <td><?= htmlspecialchars($gasto->get('categoriaNombre')) ?></td>
                <td><?= htmlspecialchars($gasto->get('valor')) ?></td>
                <td>
                    <a href="ActionsGastos/ModificarGasto.php?id=<?= htmlspecialchars($gasto->get('id')) ?>">Modificar</a>
                    <form action="ActionsGastos/Eliminar.php" method="post" style="display:inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este gasto?');">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($gasto->get('id')) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No hay gastos registrados.</p>
<?php endif; ?>

<h2>Generar Reporte</h2>
<form action="reporte_gastos.php" method="get">
    <label>Mes:</label>
    <select name="mes" required>
        <option value="enero">Enero</option>
        <option value="febrero">Febrero</option>
        <option value="marzo">Marzo</option>
        <option value="abril">Abril</option>
        <option value="mayo">Mayo</option>
        <option value="junio">Junio</option>
        <option value="julio">Julio</option>
        <option value="agosto">Agosto</option>
        <option value="septiembre">Septiembre</option>
        <option value="octubre">Octubre</option>
        <option value="noviembre">Noviembre</option>
        <option value="diciembre">Diciembre</option>
    </select>
    <br>
    
    <label for="anio">Año:</label>
    <input type="number" name="anio" value="<?= date('Y') ?>" min="1900" max="2050" required><br><br>
    
    <button type="submit">Generar Reporte</button>
</form>
</body>
</html>