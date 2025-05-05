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
        <option value="Enero">Enero</option>
        <option value="Febrero">Febrero</option>
        <option value="Marzo">Marzo</option>
        <option value="Abril">Abril</option>
        <option value="Mayo">Mayo</option>
        <option value="Junio">Junio</option>
        <option value="Julio">Julio</option>
        <option value="Agosto">Agosto</option>
        <option value="Septiembre">Septiembre</option>
        <option value="Octubre">Octubre</option>
        <option value="Noviembre">Noviembre</option>
        <option value="Diciembre">FebDiciembrerero</option>

    </select><br><br>

   <label for="id">Id</label>
    <input type="number" name="id" id="id" value="<?= isset($ing) ? htmlspecialchars($ing->id) : '' ?>" readonly><br><br>

    <label for="mes">mes:</label>
    <input type="number" name="mes" value="<?= date('M') ?>" required><br><br>

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
            <th>ID</th>
            <th>Mes</th>
            <th>Año</th>
            <th>Valor</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($ingres as $ing): ?>
            <tr>
                <td><?= htmlspecialchars($ing->getId()) ?></td>
                <td><?= htmlspecialchars($ing->getMes()) ?></td>
                <td><?= htmlspecialchars($ing->getAnio()) ?></td>
                <td><?= htmlspecialchars($ing->getValor()) ?></td>
                <td>
                    <a href="Modificar.php?id=<?= htmlspecialchars($ing->getId()) ?>">Editar</a>
                    <form action="Eliminar.php" method="post" style="display:inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este ingreso?');">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($ing->getId()) ?>">
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
