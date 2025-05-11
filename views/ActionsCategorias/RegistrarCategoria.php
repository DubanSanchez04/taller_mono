<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Categoria.php';
include '../../controllers/CategoriasController.php';

use App\controllers\CategoriasController;

$controller = new CategoriasController();
$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->saveNewCategoria($_POST);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Categoría</title>
    <link rel="stylesheet" href="../css/styleregisgastos.css">
</head>
<body>
    <h1>Resultado de la operación</h1>
    
    <?php if ($resultado === 'yes'): ?>
        <p>Categoría guardada correctamente.</p>
    <?php elseif ($resultado === 'invalid_percentage'): ?>
        <p>Error: El porcentaje debe ser mayor que 0 y no superar el 100%.</p>
    <?php else: ?>
        <p>No se pudo guardar la categoría.</p>
    <?php endif; ?>
    
    <br>
    <a href="../Categorias.php">Volver a Categorías</a>
</body>
</html>