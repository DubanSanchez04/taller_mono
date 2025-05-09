<?php
include  '../models/entities/Gasto.php';
include  '../controllers/GastosController.php';
include  '../models/drivers/ConexDB.php';
use App\controllers\GastosController;

$controlador = new GastosController();
$gas=$controlador->getAllGastos();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Gastos</title>
    <link rel="stylesheet" href="css/styleregisgastos.css">
</head>
<body>
<h1>REGISTRAR GASTOS</h1>
<?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>

<form action="ActionsGasto/Registrarg.php" method="post">
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

    <label for="valor">Valor:</label>
    <input type="number" name="valor" step="0.01" required><br><br>

    <button type="submit" name="submit">Guardar</button>
</form>

<h2>Lista de Gastos</h2>

<?php if  (!empty($gas)): ?>
    <table border="1">
        <tr>

            <th>Mes</th>
            <th>Año</th>
            <th>Valor</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($gas as $ing): ?>
            <tr>
                <td><?= htmlspecialchars($ing->get('mes')) ?></td>
                <td><?= htmlspecialchars($ing->get('anio')) ?></td>
                <td><?= htmlspecialchars($ing->get('valor')) ?></td>
                <td>
                <a href="ActionsGasto/Modificarg.php?id=<?= htmlspecialchars($ing->get('id')) ?>">Modificar</a>
                    <form action="./ActionsGasto/Eliminarg.php" method="post" style="display:inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este ingreso?');">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($ing->get('id')) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No hay ingresos registrados.</p>
<?php endif; ?>
</body>
</html>