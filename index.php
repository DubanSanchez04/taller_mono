<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Control de Gastos</title>
    <link rel="stylesheet" href="views/css/stylemenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Sistema de Control de Gastos</h1>
        <p class="subtitle">Administra tus ingresos y gastos mensuales</p>
    </div>
</header>

<div class="container">
    <div class="menu">
        <a href="views/Registroprin.php" class="menu-item">
            <i class="fas fa-money-bill-wave"></i>
            <h2>Registrar Ingresos</h2>
            <p>Registra y modifica tus ingresos mensuales</p>
        </a>

        <a href="views/Registrogas.php" class="menu-item">
            <i class="fas fa-receipt"></i>
            <h2>Gastos</h2>
            <p>Administra tus gastos por categoría</p>
        </a>

        <a href="views/Categorias.php" class="menu-item">
            <i class="fas fa-tags"></i>
            <h2>Categorías</h2>
            <p>Gestiona tus categorías de gastos</p>
        </a>

        <a href="index.php?controller=Reportes&action=index" class="menu-item">
            <i class="fas fa-chart-pie"></i>
            <h2>Reportes</h2>
            <p>Visualiza tus reportes mensuales</p>
        </a>
    </div>
</div>
</body>
</html>