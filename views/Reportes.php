<?php
use App\controllers\ReportesController;

require_once __DIR__.'/../controllers/ReportesController.php';

$controlador = new ReportesController();
$mes = $_GET['mes'] ?? date('F');
$anio = $_GET['anio'] ?? date('Y');
$reporte = $controlador->getReporteMensual($mes, $anio);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Mensual</title>
    <link rel="stylesheet" href="../css/reportes.css">
</head>
<body>
<h1>Reporte Mensual - <?= ucfirst($mes) ?> <?= $anio ?></h1>

<!-- Filtro -->
<form method="get">
    <input type="hidden" name="controller" value="Reportes">
    <input type="hidden" name="action" value="index">

    <label>Mes:</label>
    <select name="mes" required>
        <?php
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        foreach ($meses as $m) {
            echo "<option value=\"$m\" ".($m == $mes ? 'selected' : '').">$m</option>";
        }
        ?>
    </select>

    <label>Año:</label>
    <select name="anio" required>
        <?php
        $anio_actual = date('Y');
        for ($a = $anio_actual - 1; $a <= $anio_actual + 1; $a++) {
            echo "<option value=\"$a\" ".($a == $anio ? 'selected' : '').">$a</option>";
        }
        ?>
    </select>

    <button type="submit">Generar Reporte</button>
</form>

</body>
</html>