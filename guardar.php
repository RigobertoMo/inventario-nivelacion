<?php

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/validacion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$cantidad = $_POST['cantidad'] ?? '';
$stockMinimo = $_POST['stock_minimo'] ?? '';

if ($nombre === '' || $cantidad === '' || $stockMinimo === '') {
    header('Location: index.php?estado=incompleto');
    exit;
}

if (strlen($nombre) <= 3) {
    header('Location: index.php?estado=nombre_corto');
    exit;
}

if (!is_numeric($cantidad)) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

if ((int)$cantidad < 0) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

if (!validarStockMinimo($stockMinimo)) {
    header('Location: index.php?estado=stock_minimo_invalido');
    exit;
}

$sentencia = $conexion->prepare(
    'INSERT INTO productos (nombre, cantidad, stock_minimo)
     VALUES (:nombre, :cantidad, :stock_minimo)'
);

$sentencia->execute([
    'nombre' => $nombre,
    'cantidad' => (int) $cantidad,
    'stock_minimo' => (int) $stockMinimo
]);

header('Location: index.php?estado=guardado');
exit;