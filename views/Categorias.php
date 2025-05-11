<?php
include '../models/drivers/ConexDB.php';
include '../models/entities/Categoria.php';
include '../controllers/CategoriasController.php';

use App\controllers\CategoriasController;

$controlador = new CategoriasController();
$categorias = $controlador->getAllCategorias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
    <link rel="stylesheet" href="css/styleregisgastos.css">
</head>
<body>
    <h1>GESTIÓN DE CATEGORÍAS</h1>
    
    <form action="ActionsCategorias/RegistrarCategoria.php" method="post">
        <h2>Nueva Categoría</h2>
        <label for="name">Nombre:</label>
        <input type="text" name="name" required><br><br>
        
        <label for="percentage">Porcentaje:</label>
        <input type="number" name="percentage" step="0.01" min="0.01" max="100" required><br>
        <small>El porcentaje debe ser mayor que 0 y no superar el 100%</small><br><br>
        
        <button type="submit">Guardar Categoría</button>
    </form>
    
    <h2>Lista de Categorías</h2>
    
    <?php if (!empty($categorias)): ?>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Porcentaje</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($categorias as $categoria): ?>
        <tr>
            <td><?= htmlspecialchars($categoria->get('name')) ?></td>
            <td><?= htmlspecialchars($categoria->get('percentage')) ?>%</td>
            <td>
                <a href="ActionsCategorias/ModificarCategoria.php?id=<?= htmlspecialchars($categoria->get('id')) ?>">Modificar</a>
                <form action="ActionsCategorias/EliminarCategoria.php" method="post" style="display:inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($categoria->get('id')) ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>No hay categorías registradas.</p>
    <?php endif; ?>
    
    <br>
    <a href="Registrogas.php">Volver a Registro de Gastos</a>
</body>
</html>