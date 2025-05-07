<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Gasto.php';
include '../../controllers/GastosController.php';

use App\controllers\GastosController;

$controller = new GastosController();
$res = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id']) && isset($_POST['valor'])) {
    $data = [
        'id' => $_GET['id'],
        'valor' => $_POST['valor']
    ];
    $res = $controller->updateGasto($data);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
<h2>Modificar Valor de Gasto</h2>

<form method="POST">
    <label>Nuevo valor:</label>
    <input type="number" step="0.01" name="valor" required><br><br>

    <input type="submit" value="Modificar">
</form>

<?php if ($res === 'yes'): ?>
    <p>Datos actualizados.</p>
<?php elseif ($res === 'not'): ?>
    <p>Datos no actualizados.</p>
<?php endif; ?>

<br>
<a href="../Registrogas.php">Volver</a>
</body>
</html>
