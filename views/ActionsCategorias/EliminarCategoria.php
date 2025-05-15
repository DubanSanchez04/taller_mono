<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Categoria.php';
include '../../controllers/CategoriasController.php';

use App\controllers\CategoriasController;

$controller = new CategoriasController();
$res = null;

if (isset($_POST['id'])) {
    $res = $controller->removeCategoria($_POST['id']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Categoría</title>
    <link rel="stylesheet" href="../css/styleregisgastos.css">
</head>
<body>
    <h2>Resultado de la operación</h2>
    
    <?php if ($res === 'yes'): ?>
        <p>Categoría eliminada correctamente.</p>
    <?php elseif ($res === 'linked_to_expenses'): ?>
        <p>Error: No se puede eliminar esta categoría porque está vinculada a gastos existentes.</p>
    <?php else: ?>
        <p>Error al eliminar la categoría.</p>
    <?php endif; ?>
    
    <br>
    <a href="../Categorias.php">Volver a Categorías</a>
</body>
</html>