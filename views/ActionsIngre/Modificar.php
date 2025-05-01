<?php
require_once __DIR__ . '/../../controllers/IngresosController.php';

use App\controllers\IngresosController;

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller = new IngresosController();
    $resultado = $controller->modificar([
        "id" => $_POST["id"],
        "valor" => $_POST["valor"]
    ]);
    $mensaje = $resultado["message"];
}
?>

<h2>Modificar Valor de Ingreso</h2>

<form method="POST">
    <label>ID del ingreso:</label>
    <input type="number" name="id" required><br><br>

    <label>Nuevo valor:</label>
    <input type="number" step="0.01" name="valor" required><br><br>

    <input type="submit" value="Modificar">
</form>

<?php if ($mensaje): ?>
    <p><?= $mensaje ?></p>
<?php endif; ?>

