<?php
include '../../models/entities/Gasto.php';
include '../../controllers/GastosController.php';
include '../../models/drivers/ConexDB.php';

use App\controllers\GastosController;

$controlador = new GastosController();
$categorias = $controlador->getCategorias();
$reportes = $controlador->getReportes();

// Obtener el gasto a modificar
$id = $_GET['id'] ?? null;
$gasto = null;

if ($id) {
    $gastos = $controlador->getAllGastos();
    foreach ($gastos as $g) {
        if ($g->get('id') == $id) {
            $gasto = $g;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $valor = $_POST['valor'];
    $idCategoria = $_POST['categoria'];
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];
    
    // Verificar si ya existe un reporte para este mes/año
    $idReporte = $controlador->getReporteByMesAnio($mes, $anio);
    
    if (!$idReporte) {
        // Si no existe, crear uno nuevo
        $idReporte = $controlador->createReporte($mes, $anio);
    }
    
    // Actualizar el gasto
    if ($controlador->updateGasto($id, $valor, $idCategoria, $idReporte)) {
        $mensaje = "Gasto actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar el gasto.";
    }
    
    header("Location: ../gastos.php?mensaje=" . urlencode($mensaje));
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modificar Gasto</title>
    <link rel="stylesheet" href="../css/styleregis.css">
</head>
<body>
<h1>Modificar Gasto</h1>

<?php if ($gasto): 
    // Buscar el reporte del gasto
    $reporteGasto = null;
    foreach ($reportes as $reporte) {
        if ($reporte->get('id') == $gasto->get('idReporte')) {
            $reporteGasto = $reporte;
            break;
        }
    }
?>
    <form action="ModificarGasto.php" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($gasto->get('id')) ?>">
        
        <label>Mes:</label>
        <select name="mes" required>
            <?php 
            $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 
                     'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            foreach ($meses as $mes): 
                $selected = ($reporteGasto && strtolower($reporteGasto->get('month')) == $mes) ? 'selected' : '';
            ?>
                <option value="<?= $mes ?>" <?= $selected ?>><?= ucfirst($mes) ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        
        <label for="anio">Año:</label>
        <input type="number" name="anio" value="<?= $reporteGasto ? htmlspecialchars($reporteGasto->get('year')) : date('Y') ?>" min="1900" max="2050" required><br><br>
        
        <label for="categoria">Categoría:</label>
        <select name="categoria" required>
            <?php foreach ($categorias as $categoria): 
                $selected = ($gasto->get('idCategoria') == $categoria->get('id')) ? 'selected' : '';
            ?>
                <option value="<?= htmlspecialchars($categoria->get('id')) ?>" <?= $selected ?>>
                    <?= htmlspecialchars($categoria->get('nombre')) ?> (<?= $categoria->get('porcentaje') ?>%)
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label for="valor">Valor:</label>
        <input type="number" name="valor" step="0.01" value="<?= htmlspecialchars($gasto->get('valor')) ?>" required><br><br>
        
        <button type="submit">Actualizar</button>
    </form>
<?php else: ?>
    <p>Gasto no encontrado.</p>
<?php endif; ?>

<p><a href="../gastos.php">Volver a Gastos</a></p>
</body>
</html>