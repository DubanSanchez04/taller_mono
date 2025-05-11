<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Gasto.php';
include '../../controllers/GastosController.php';

use App\controllers\GastosController;

$controller = new GastosController();
$res = null;
$gasto = null;

// Obtener el gasto actual para mostrar sus valores actuales
if (isset($_GET['id'])) {
    $gastoObj = new App\models\entities\Gasto();
    $gastoObj->set('id', $_GET['id']);
    $gasto = $gastoObj->find();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id']) && isset($_POST['valor'])) {
    $data = [
        'id' => $_GET['id'],
        'valor' => $_POST['valor']
    ];
    $res = $controller->updateGasto($data);
    
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
<h2>Modificar Valor de Gasto</h2>

<?php if ($gasto): ?>
<form method="POST">
    <label>Valor actual: <?= htmlspecialchars($gasto->get('valor')) ?></label><br><br>
    <label>Nuevo valor:</label>
    <input type="number" step="0.01" name="valor" value="<?= htmlspecialchars($gasto->get('valor')) ?>" required><br><br>

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