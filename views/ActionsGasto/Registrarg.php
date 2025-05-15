<?php
include '../../models/drivers/ConexDB.php';
include '../../models/entities/Gasto.php';
include '../../controllers/GastosController.php';

use App\controllers\GastosController;

$controller = new GastosController();
$resultado = $controller->saveNewGasto($_POST);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Gasto</title>
    <link rel="stylesheet" href="../css/styleregisgastos.css">
</head>

<body>
<h1>Resultado de la operación</h1>
<?php
if ($resultado == 'yes') {
    echo '<p>Gasto registrado correctamente.</p>';
} elseif ($resultado == 'invalid_percentage') {
    echo '<p>Error: El porcentaje debe ser mayor que 0 y no superar el 100%.</p>';
} elseif ($resultado == 'category_not_found') {
    echo '<p>Error: Categoría no encontrada.</p>';
} else {
    echo '<p>No se pudo guardar los datos.</p>';
}
?>
<br>
<a href="../Registrogas.php">Volver a Registro de Gastos</a>
</body>

</html>