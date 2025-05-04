<?php
include '../../models/db.php/ConexDB.php';
include '../../models/entities/Ingreso.php';
include '../../controllers/IngresoController.php';

use App\controllers\IngresosController;

$controller = new IngresosController();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('location: ../views/Ingresos/Ingresos.php');
}
$res=empty($_POST['idPerson'])
    ? $controller->saveNewPerson($_POST)
    : $controller->updatePerson($_POST);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado operación</title>
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
