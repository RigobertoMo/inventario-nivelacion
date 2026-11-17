<?php
function validarStockMinimo($stock) {
    if (is_string($stock)) {
        $stock = trim($stock);
    }

    if (!is_numeric($stock) || (string)(int)$stock !== (string)$stock) {
        return false;
    }

    $stock = (int)$stock;
    return $stock >= 5 && $stock <= 100;
}