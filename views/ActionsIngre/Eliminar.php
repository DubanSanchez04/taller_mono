<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Ingreso.php';
include '../../controllers/IngresosController.php';

use App\controllers\IngresosController;

$controller = new IngresosController();
$res = null;

if (isset($_POST['id'])) {
    $res = $controller->removeIngreso($_POST['id']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar</title>
</head>
<body>
<h2>Registro eliminado.</h2>
<?php if ($res === 'yes'): ?>
    <p>Registro eliminado.</p>
<?php elseif ($res === 'not'): ?>
    <p>Error al eliminar el registro..</p>
<?php endif; ?>
<a href="../Registroprin.php">Volver</a>
</body>
</html>





