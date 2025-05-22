<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Mensual</title>
    <link rel="stylesheet" href="views/css/Reportes.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
        .exceso {
            background-color: #fdd;
            color: #a00;
            font-weight: bold;
        }
        .ok {
            background-color: #dfd;
            color: #080;
        }
        .advertencia {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }
        .bueno {
            color: green;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<h2>Reporte de <?= $mes ?? 'mes no definido' ?> de <?= $anio ?? 'año no definido' ?></h2>
<p>Ingreso: <?= isset($ingreso) ? number_format($ingreso, 2) : '0.00' ?></p>
<p>Ahorro: <?= isset($ahorro) ? number_format($ahorro, 2) : '0.00' ?> (<?= isset($porcentajeAhorro) ? round($porcentajeAhorro, 2) : '0' ?>%)</p>

<table>
    <thead><tr><th>Categoría</th><th>Gasto</th><th>Límite (%)</th><th>Advertencia</th></tr></thead>
    <tbody>
    <?php
    if (!empty($detalles) && is_array($detalles)) {
        foreach ($detalles as $fila):
            ?>
            <tr class="<?= $fila['exceso'] ? 'exceso' : 'ok' ?>">
                <td><?= htmlspecialchars($fila['categoria']) ?></td>
                <td>$<?= number_format($fila['gasto'], 2) ?></td>
                <td><?= $fila['limite'] ?>%</td>
                <td><?= $fila['exceso'] ? '⚠ Exceso' : '' ?></td>
            </tr>
        <?php
        endforeach;
    } else {
        echo "<tr><td colspan='4'>No hay detalles para mostrar.</td></tr>";
    }
    ?>
    </tbody>
</table>

<p><strong>Ahorro del mes:</strong> $<?= isset($ahorro) ? number_format($ahorro, 2) : '0.00' ?> (<?= isset($porcentajeAhorro) ? round($porcentajeAhorro, 2) : '0' ?>%)</p>

<?php if (isset($porcentajeAhorro) && $porcentajeAhorro < 10): ?>
    <p class="advertencia"> Advertencia: No hubo suficiente ahorro. Ahorro menor al 10%.</p>
<?php elseif (isset($porcentajeAhorro)): ?>
    <p class="bueno"> Excelente: Tu ahorro es mayor o igual al 10%.</p>
<?php endif; ?>
</body>
<div>
    <a href="../index.php" class="menu-item">
        <h2>Volver</h2>
    </a>

</div>
</html>
