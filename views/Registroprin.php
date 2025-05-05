<?php
include  '../models/entities/Ingreso.php';
include  '../controllers/IngresosController.php';
include  '../models/drivers/ConexDB.php';
use App\controllers\IngresosController;

$controlador = new IngresosController();
$ingres=$controlador->getAllIngresos();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrar Ingreso</title>
    <link rel="stylesheet" href="css/styleregis.css">

</head>
<body>
<h1>Registrar Ingreso</h1>

<?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>


<form action="ActionsIngre/Registrar.php" method="post">
    <label>Mes:</label>
    <select name="mes" required>
        <option value="1">Enero</option>
        <option value="2">Febrero</option>
        <option value="3">Marzo</option>
        <option value="4">Abril</option>
        <option value="5">Mayo</option>
        <option value="6">Junio</option>
        <option value="7">Julio</option>
        <option value="8">Agosto</option>
        <option value="9">Septiembre</option>
        <option value="10">Octubre</option>
        <option value="11">Noviembre</option>
        <option value="12">Diciembre</option>

    </select>
    <br>
    <label for="anio">Año:</label>
    <input type="number" name="anio" value="<?= date('Y') ?>" min="1900" max="2050" required><br><br>

    <label for="valor">Valor:</label>
    <input type="number" name="valor" step="0.01" required><br><br>

    <button type="submit" name="submit">Guardar</button>
</form>

<h2>Lista de Ingresos</h2>

<?php if  (!empty($ingres)): ?>
    <table border="1">
        <tr>
<!--            <th>ID</th>-->
            <th>Mes</th>
            <th>Año</th>
            <th>Valor</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($ingres as $ing): ?>
            <tr>
<!--                <td>--><?php //= htmlspecialchars($ing->getId()) ?><!--</td>-->
                <td><?= htmlspecialchars($ing->get('mes')) ?></td>
                <td><?= htmlspecialchars($ing->get('anio')) ?></td>
                <td><?= htmlspecialchars($ing->get('valor')) ?></td>
                <td>
                    <a href="Modificar.php?id=<?= htmlspecialchars($ing->get('id')) ?>">Editar</a>
                    <form action="Eliminar.php" method="post" style="display:inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este ingreso?');">
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
