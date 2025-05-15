<?php require_once '../partials/header.php'; ?>

<div class="container">
    <h1>Reporte Mensual</h1>

    <!-- Filtro -->
    <form method="get" class="filtro">
        <input type="hidden" name="controller" value="Reportes">
        <input type="hidden" name="action" value="mensual">

        <div class="form-group">
            <label>Mes:</label>
            <select name="mes">
                <?php
                $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                foreach ($meses as $m) {
                    $selected = $m == $mes ? 'selected' : '';
                    echo "<option value=\"$m\" $selected>$m</option>";
                }
                ?>
            </select>
        </div>

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

    <!-- Resumen -->
    <div class="resumen">
        <div class="card ingreso">
            <h3>Ingresos</h3>
            <p><?= $datos['ingreso'] ? '$' . number_format($datos['ingreso']['value'], 2) : 'No registrado' ?></p>
        </div>

        <div class="card gasto">
            <h3>Gastos</h3>
            <p>$<?= number_format($datos['total_gastos'], 2) ?></p>
        </div>

        <div class="card ahorro <?= $datos['porcentaje_ahorro'] >= 10 ? 'positivo' : 'negativo' ?>">
            <h3>Ahorro</h3>
            <p>$<?= number_format($datos['ahorro'], 2) ?></p>
            <p><?= number_format($datos['porcentaje_ahorro'], 2) ?>%</p>
            <?php if ($datos['porcentaje_ahorro'] < 10): ?>
                <p class="alerta">¡Meta no alcanzada!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Detalle por categoría -->
    <h2>Gastos por Categoría</h2>
    <table class="tabla-gastos">
        <thead>
        <tr>
            <th>Categoría</th>
            <th>Presupuesto</th>
            <th>Gastado</th>
            <th>Valor</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($datos['gastos'] as $gasto):
            $porcentaje = $datos['ingreso'] ? ($gasto['value'] / $datos['ingreso']['value'] * 100) : 0;
            $excedido = $porcentaje > $gasto['porcentaje_categoria'];
            ?>
            <tr>
                <td><?= $gasto['categoria_nombre'] ?></td>
                <td><?= $gasto['porcentaje_categoria'] ?>%</td>
                <td><?= number_format($porcentaje, 2) ?>%</td>
                <td>$<?= number_format($gasto['value'], 2) ?></td>
                <td class="<?= $excedido ? 'excedido' : 'ok' ?>">
                    <?= $excedido ? 'Excedido' : 'OK' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="acciones">
        <a href="?controller=Reportes&action=anual" class="btn">Ver Reporte Anual</a>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>
