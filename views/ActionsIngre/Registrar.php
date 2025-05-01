<?php
require_once __DIR__ . '/../../models/entities/Ingreso.php';
require_once __DIR__ . '/../../controllers/IngresosController.php';

use App\models\entities\Ingreso;
use App\controllers\IngresosController;

$controller = new IngresosController();

// Registrar nuevo ingreso
if (isset($_POST['submit'])) {
    $result = $controller->registrar($_POST);

    if ($result['success']) {
        $message = "<div class='success'>{$result['message']}</div>";
    } else {
        $message = "<div class='error'>{$result['message']}</div>";
    }
}

// Obtener todos los ingresos registrados
$ingresos = $controller->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar y Listar Ingresos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        .form-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            grid-column: 1 / -1;
        }
        button:hover {
            background-color: #2980b9;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .actions a {
            padding: 5px 10px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
        }
        .actions a:hover {
            background-color: #27ae60;
        }
        .actions form {
            display: inline;
        }
        .actions button {
            padding: 5px 10px;
            background-color: #e74c3c;
            font-size: 14px;
            margin: 0;
        }
        .actions button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Registrar Ingreso</h1>

    <?php if (!empty($message)) echo $message; ?>

    <div class="form-container">
        <form method="post">
            <div>
                <label>Mes:</label>
                <select name="mes" required>
                    <?php foreach (Ingreso::$mesesValidos as $mes): ?>
                        <option value="<?= htmlspecialchars($mes) ?>"><?= htmlspecialchars($mes) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Año:</label>
                <input type="number" name="año" min="2000" max="2100" value="<?= date('Y') ?>" required>
            </div>

            <div>
                <label>Valor:</label>
                <input type="number" name="valor" min="0.01" step="0.01" required>
            </div>

            <button type="submit" name="submit">Registrar Ingreso</button>
        </form>
    </div>

    <h2>Ingresos Registrados</h2>
    <?php if (!empty($ingresos['ingresos']) && is_array($ingresos['ingresos'])): ?>
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
            <?php foreach ($ingresos['ingresos'] as $ingreso): ?>
                <tr>
                    <td><?= htmlspecialchars($ingreso['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($ingreso['mes'] ?? '') ?></td>
                    <td><?= htmlspecialchars($ingreso['año'] ?? '') ?></td>
                    <td><?= number_format($ingreso['valor'] ?? 0, 2) ?></td>
                    <td class="actions">
                        <a href="Modificar.php?id=<?= $ingreso['id'] ?>">Editar</a>
                        <form action="Eliminar.php" method="post" onsubmit="return confirm('¿Estás seguro?')">
                            <input type="hidden" name="id" value="<?= $ingreso['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($ingresos['success']): ?>
        <p>No hay ingresos registrados aún.</p>
    <?php else: ?>
        <p class="error">Error al cargar los ingresos: <?= htmlspecialchars($ingresos['message'] ?? 'Error desconocido') ?></p>
    <?php endif; ?>
</div>
</body>
</html>