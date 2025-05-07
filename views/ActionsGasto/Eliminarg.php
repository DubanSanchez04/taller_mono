<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Gasto.php';
include '../../controllers/GastosController.php';

use App\controllers\GastosController;

$controller = new GastosController();
$res = null;

if (isset($_POST['id'])) {
    $res = $controller->removeGasto($_POST['id']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar</title>
</head>
<body>
<h2>Registro eliminado correctamente.</h2>
<?php if ($res === 'yes'): ?>
    <p>Registro eliminado.</p>
<?php elseif ($res === 'not'): ?>
    <p>Error al eliminar el registro..</p>
<?php endif; ?>
<a href="../Registrogas.php">Volver</a>
</body>
</html>



