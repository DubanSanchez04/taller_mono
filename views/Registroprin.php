<?php
include  '../models/entities/Ingreso.php';
include  '../models/entities/model.php';
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
    <link rel="stylesheet" href="../css/styleregis.css">

</head>
<body>
<h1>Registrar Ingreso</h1>

<?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>


<form action="registrar.php" method="post">
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

    <label>Año:</label>
    <input type="number" name="año" value="<?= date('Y') ?>" required><br><br>

    <label>Valor:</label>
    <input type="number" name="valor" step="0.01" required><br><br>

    <button type="submit" name="submit">Guardar</button>
</form>

<h2>Lista de Ingresos</h2>

<?php if (!empty($ingresos['ingresos'])): ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Mes</th>
            <th>Año</th>
            <th>Valor</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($ingresos['ingresos'] as $ing): ?>
            <tr>
                <td><?= $ing['id'] ?></td>
                <td><?= $ing['mes'] ?></td>
                <td><?= $ing['año'] ?></td>
                <td><?= number_format($ing['valor'], 2) ?></td>
                <td>
                    <a href="Modificar.php?id=<?= $ing['id'] ?>">Editar</a>
                    <form action="Eliminar.php" method="post" style="display:inline">
                        <input type="hidden" name="id" value="<?= $ing['id'] ?>">
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
