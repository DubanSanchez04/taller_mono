<?php require_once '../partials/header.php'; ?>

<div class="container">
    <h1>Reporte Anual</h1>

    <!-- Filtro -->
    <form method="get" class="filtro">
        <input type="hidden" name="controller" value="Reportes">
        <input type="hidden" name="action" value="anual">

        <div class="form-group">
            <label>Año:</label>
            <select name="anio">
                <?php
                $anio_actual = date('Y');
                for ($a = $anio_actual - 1; $a <= $anio_actual + 1; $a++) {
                    $selected = $a == $anio ? 'selected' : '';
                    echo "<option value=\"$a\" $selected>$a</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit">Generar</button>
    </form>

    <!-- Tabla anual -->
    <table class="tabla-anual">
        <thead>
        <tr>
            <th>Mes</th>
            <th>Ingresos</th>
            <th>Gastos</th>
            <th>Ahorro</th>
            <th>% Ahorro</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($datos as $mes):
            $ahorro = $mes['total_ingresos'] - $mes['total_gastos'];
            $porcentaje_ahorro = $mes['total_ingresos'] ? ($ahorro / $mes['total_ingresos'] * 100) : 0;
            ?>
            <tr>
                <td><?= $mes['month'] ?></td>
                <td>$<?= number_format($mes['total_ingresos'] ?? 0, 2) ?></td>
                <td>$<?= number_format($mes['total_gastos'] ?? 0, 2) ?></td>
                <td>$<?= number_format($ahorro, 2) ?></td>
                <td><?= number_format($porcentaje_ahorro, 2) ?>%</td>
                <td class="<?= $porcentaje_ahorro >= 10 ? 'positivo' : 'negativo' ?>">
                    <?= $porcentaje_ahorro >= 10 ? 'Meta alcanzada' : 'Meta no alcanzada' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="acciones">
        <a href="?controller=Reportes&action=mensual" class="btn">Ver Reporte Mensual</a>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>
