<?php

$action = $_GET['action'] ?? 'listar';

switch ($action) {
    case 'registrar':
        header("Location: views/Registroprin.php");
        break;
    case 'listar':

}

header("location: views/Registroprin.php");
