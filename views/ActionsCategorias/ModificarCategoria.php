<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Categoria.php';
include '../../controllers/CategoriasController.php';

use App\controllers\CategoriasController;

$controller = new CategoriasController();
$res = null;
$categoria = null;

// Obtener la categoría actual
if (isset($_GET['id'])) {
    $categoria = $controller->findCategoria($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $res = $controller->updateCategoria($_POST);
    
    // Redirigir a la página principal si la actualización fue exitosa
    if ($res === 'yes') {
        header('Location: ../Categorias.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Categoría</title>
    <link rel="stylesheet" href="../css/styleregisgastos.css">
</head>
<body>
    <h2>Modificar Categoría</h2>
    
    <?php if ($categoria): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($categoria->get('id')) ?>">
        
        <label for="name">Nombre:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($categoria->get('name')) ?>" required><br><br>
        
        <label for="percentage">Porcentaje:</label>
        <input type="number" name="percentage" step="0.01" min="0.01" max="100" value="<?= htmlspecialchars($categoria->get('percentage')) ?>" required><br>
        <small>El porcentaje debe ser mayor que 0 y no superar el 100%</small><br><br>
        
        <input type="submit" value="Modificar">
    </form>
    <?php else: ?>
        <p>No se encontró la categoría solicitada.</p>
    <?php endif; ?>
    
    <?php if ($res === 'yes'): ?>
        <p>Categoría actualizada correctamente.</p>
    <?php elseif ($res === 'invalid_percentage'): ?>
        <p>Error: El porcentaje debe ser mayor que 0 y no superar el 100%.</p>
    <?php elseif ($res === 'linked_to_expenses'): ?>
        <p>Error: No se puede modificar esta categoría porque está vinculada a gastos existentes.</p>
    <?php elseif ($res === 'not'): ?>
        <p>Error al actualizar la categoría.</p>
    <?php endif; ?>
    
    <br>
    <a href="../Categorias.php">Volver a Categorías</a>
</body>
</html>