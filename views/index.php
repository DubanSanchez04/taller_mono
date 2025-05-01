<?php
// En index.php
$action = $_GET['action'] ?? 'listar';

switch ($action) {
    case 'registrar':
        header("Location: views/ActionsIngre/Registrar.php");
        break;
    case 'listar':
    default:
        header("Location: views/ActionsIngre/Listar.php");
        break;
}