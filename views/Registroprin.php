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

<h2>Lista de Ingresos</h2>

<?php if  (!empty($ingres)): ?>
    <table border="1">
        <tr>

            <th>Mes</th>
            <th>Año</th>
            <th>Valor</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($ingres as $ing): ?>
            <tr>
                <td><?= htmlspecialchars($ing->get('mes')) ?></td>
                <td><?= htmlspecialchars($ing->get('anio')) ?></td>
                <td><?= htmlspecialchars($ing->get('valor')) ?></td>
                <td>
                    <a href="./ActionsIngre/Modificar.php?id=<?= htmlspecialchars($ing->get('id')) ?>">Editar</a>
                    <form action="./ActionsIngre/Eliminar.php" method="post" style="display:inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este ingreso?');">
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
