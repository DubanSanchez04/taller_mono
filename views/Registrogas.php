<?php
include  '../models/entities/Gasto.php';
include  '../controllers/GastosController.php';
include  '../models/drivers/ConexDB.php';
include  '../models/entities/Categoria.php';
include  '../controllers/CategoriasController.php';

use App\controllers\GastosController;
use App\controllers\CategoriasController;

$gastoController = new GastosController();
$categoriaController = new CategoriasController();
$gas = $gastoController->getAllGastos();
$categorias = $categoriaController->getAllCategorias();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Gastos</title>
    <link rel="stylesheet" href="css/styleregisgas.css">
</head>
<body>
<h1>REGISTRAR GASTOS</h1>
<?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>

<div style="text-align: right; margin-bottom: 20px;">
    <a href="Categorias.php" style="padding: 8px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Gestionar Categorías</a>
</div>

<form action="ActionsGasto/Registrarg.php" method="post">
    <label>Mes:</label>
    <select name="mes" required>
        <?php foreach (App\models\entities\Gasto::$mesesValidos as $mes): ?>
            <option value="<?= strtolower($mes) ?>"><?= $mes ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <label for="anio">Año:</label>
    <input type="number" name="anio" value="<?= date('Y') ?>" min="1900" max="2050" required><br><br>

    <label for="valor">Valor:</label>
    <input type="number" name="valor" step="0.01" required><br><br>

    <label for="categoria">Categoría:</label>
    <select name="categoria" id="categoria" required>
        <option value="">Seleccione una categoría</option>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?= htmlspecialchars($categoria->get('name')) ?>" 
                   data-id="<?= htmlspecialchars($categoria->get('id')) ?>">
                <?= htmlspecialchars($categoria->get('name')) ?> (<?= htmlspecialchars($categoria->get('percentage')) ?>%)
            </option>
        <?php endforeach; ?>
        <option value="Otro">Otro</option>
    </select><br><br>
    
    <div id="otraCategoria" style="display: none;">
        <label for="nuevaCategoria">Nueva Categoría:</label>
        <input type="text" name="nuevaCategoria" id="nuevaCategoriaInput"><br><br>
        
        <label for="porcentaje">Porcentaje (%):</label>
        <input type="number" name="porcentaje" id="porcentajeInput" step="0.01" min="0.01" max="100"><br>
        <small>El porcentaje debe ser mayor que 0 y no superar el 100%</small><br><br>
    </div>

    <button type="submit" name="submit">Guardar</button>
</form>

<h2>Lista de Gastos</h2>

<?php if (!empty($gas)): ?>
   <table border="1">
    <tr>
        <th>Mes</th>
        <th>Año</th>
        <th>Valor</th>
        <th>Categoría y porcentaje</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($gas as $ing): ?>
        <tr>
            <td><?= htmlspecialchars($ing->get('mes')) ?></td>
            <td><?= htmlspecialchars($ing->get('anio')) ?></td>
            <td><?= htmlspecialchars($ing->get('valor')) ?></td>
            <td><?= htmlspecialchars($ing->get('categoria')) ?> (<?= htmlspecialchars($ing->get('porcentaje')) ?>%)</td>
            <td>
                <a href="ActionsGasto/Modificarg.php?id=<?= htmlspecialchars($ing->get('id')) ?>">Modificar</a>
                <form action="./ActionsGasto/Eliminarg.php" method="post" style="display:inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este gasto?');">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($ing->get('id')) ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php else: ?>
    <p>No hay Gastos registrados.</p>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const categoriaSelect = document.getElementById('categoria');
    const otraCategoriaDiv = document.getElementById('otraCategoria');
    

    categoriaSelect.addEventListener('change', function() {
        if (this.value === 'Otro') {
            otraCategoriaDiv.style.display = 'block';
            document.getElementById('porcentajeInput').value = '';
            document.getElementById('nuevaCategoriaInput').focus();
        } else {
            otraCategoriaDiv.style.display = 'none';
        }
    });
});
</script>
<div>
    <a href="../index.php" class="menu-item">
        <h2>Volver</h2>
    </a>

</div>
</body>
</html>