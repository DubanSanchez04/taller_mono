<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Ingreso.php';
include '../../controllers/IngresosController.php';

use App\controllers\IngresosController;

$controller = new IngresosController();

$res=empty($_POST['id']);
$controller->saveNewIngreso($_POST);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso</title>
</head>

<body>
<h1>Resultado de la operación</h1>
<?php
if ($res == 'yes') {
    echo '<p>Datos guardados</p>';
} else {
    echo  '<p>No se pudo guardar los datos</p>';
}
?>
<br>
<a href="../Registroprin.php">Volver</a>
</body>

</html>
