<?php
require_once __DIR__ . '/../../../controllers/IngresosController.php';
$controller = new \App\controllers\IngresosController();
$resultado = $controller->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Ingresos</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; padding: 10px; }
        .error { color: red; padding: 10px; }
        .actions { display: flex; gap: 5px; }
    </style>
</head>
<body>
<h1 style="text-align: center;">Listado de Ingresos</h1>

<div style="text-align: center; margin: 20px;">
    <a href="Registrar.php">Registrar Nuevo Ingreso</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<?php if ($resultado['success']) : ?>
    <?php if (!empty($resultado['ingresos'])) : ?>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Mes</th>
                <th>Año</th>
                <th>Valor</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado['ingresos'] as $ingreso) : ?>
                <tr>
                    <td><?= htmlspecialchars($ingreso['id']) ?></td>
                    <td><?= htmlspecialchars($ingreso['mes']) ?></td>
                    <td><?= htmlspecialchars($ingreso['año']) ?></td>
                    <td><?= number_format($ingreso['valor'], 2) ?></td>
                    <td class="actions">
                        <a href="Modificar.php?id=<?= $ingreso['id'] ?>">Editar</a>
                        <form action="Eliminar.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $ingreso['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p style="text-align: center;">No hay ingresos registrados.</p>
    <?php endif; ?>
<?php else : ?>
    <div class="error"><?= htmlspecialchars($resultado['message']) ?></div>
<?php endif; ?>
</body>
</html>