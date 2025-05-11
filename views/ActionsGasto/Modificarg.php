<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Gasto.php';
include '../../models/entities/Categoria.php';
include '../../controllers/GastosController.php';
include '../../controllers/CategoriasController.php';

use App\controllers\GastosController;
use App\controllers\CategoriasController;

$gastoController = new GastosController();
$categoriaController = new CategoriasController();
$res = null;
$gasto = null;

// Obtener todas las categorías para el selector
$categorias = $categoriaController->getAllCategorias();

// Obtener el gasto actual para mostrar sus valores actuales
if (isset($_GET['id'])) {
    $gastoObj = new App\models\entities\Gasto();
    $gastoObj->set('id', $_GET['id']);
    $gasto = $gastoObj->find();
    
    // Obtener información de la categoría y reporte para este gasto
    if ($gasto) {
        $db = new App\models\drivers\ConexDB();
        $sql = "SELECT b.id, b.value, c.id AS idCategory, c.name AS categoria, 
                r.month AS mes, r.year AS anio, c.percentage AS porcentaje
                FROM bills b
                JOIN reports r ON b.idReport = r.id
                JOIN categories c ON b.idCategory = c.id
                WHERE b.id = ?";
        $stmt = $db->prepare($sql);
        $id = $gasto->get('id');
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $gasto->set('categoria', $row['categoria']);
            $gasto->set('mes', $row['mes']);
            $gasto->set('anio', $row['anio']);
            $gasto->set('porcentaje', $row['porcentaje']);
            $gasto->set('idCategory', $row['idCategory']);
        }
        
        $stmt->close();
        $db->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $data = [
        'id' => $_GET['id'],
        'valor' => $_POST['valor'],
        'idCategory' => $_POST['categoria']
    ];
    $res = $gastoController->updateGasto($data);
    
    // Redirigir a la página principal si la actualización fue exitosa
    if ($res === 'yes') {
        header('Location: ../Registrogas.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Gasto</title>
    <link rel="stylesheet" href="../css/styleregisgastos.css">
</head>
<body>
<h2>Modificar Gasto</h2>

<?php if ($gasto): ?>
<form method="POST">
    <label>Mes: <?= htmlspecialchars($gasto->get('mes')) ?></label><br>
    <label>Año: <?= htmlspecialchars($gasto->get('anio')) ?></label><br><br>
    
    <label for="valor">Valor:</label>
    <input type="number" step="0.01" name="valor" value="<?= htmlspecialchars($gasto->get('valor')) ?>" required><br><br>
    
    <label for="categoria">Categoría:</label>
    <select name="categoria" required>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?= $categoria->get('id') ?>" 
                <?= ($categoria->get('id') == $gasto->get('idCategory')) ? 'selected' : '' ?>>
                <?= htmlspecialchars($categoria->get('name')) ?> (<?= $categoria->get('percentage') ?>%)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <input type="submit" value="Modificar">
</form>
<?php else: ?>
    <p>No se encontró el gasto solicitado.</p>
<?php endif; ?>

<?php if ($res === 'yes'): ?>
    <p>Datos actualizados.</p>
<?php elseif ($res === 'not'): ?>
    <p>Datos no actualizados.</p>
<?php endif; ?>

<br>
<a href="../Registrogas.php">Volver</a>
</body>
</html>