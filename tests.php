<?php
require_once 'validacion.php';

echo "========== PRUEBAS UNITARIAS ==========\n\n";

function assertEqual($expected, $actual, $message) {
    if ($expected === $actual) {
        echo "PASS: $message\n";
        return true;
    } else {
        echo "FAIL: $message\n";
        echo "  Esperado: " . var_export($expected, true) . "\n";
        echo "  Obtenido: " . var_export($actual, true) . "\n";
        return false;
    }
}

$passed = 0;
$total = 0;

// U1 - Valor válido intermedio
$total++; $passed += assertEqual(true, validarStockMinimo(50), "U1: Stock 50 (válido)");

// U2 - Límite inferior
$total++; $passed += assertEqual(true, validarStockMinimo(5), "U2: Stock 5 (límite inferior)");

// U3 - Límite superior
$total++; $passed += assertEqual(true, validarStockMinimo(100), "U3: Stock 100 (límite superior)");

// U4 - Inválido por debajo
$total++; $passed += assertEqual(false, validarStockMinimo(4), "U4: Stock 4 (inválido)");

// U5 - Inválido por encima
$total++; $passed += assertEqual(false, validarStockMinimo(101), "U5: Stock 101 (inválido)");

echo "\nResultado unitarias: $passed/$total\n\n";

echo "========== PRUEBAS DE INTEGRACIÓN ==========\n\n";

// Simulación de base de datos en memoria
class ProductoDB {
    private $productos = [];

    public function guardar($nombre, $stockMinimo) {
        if (!validarStockMinimo($stockMinimo)) {
            return false;
        }
        $id = count($this->productos) + 1;
        $this->productos[$id] = [
            'id' => $id,
            'nombre' => $nombre,
            'stock_minimo' => (int)$stockMinimo
        ];
        return $id;
    }

    public function recuperar($id) {
        return $this->productos[$id] ?? null;
    }

    public function limpiar() {
        $this->productos = [];
    }

    public function contar() {
        return count($this->productos);
    }
}

$db = new ProductoDB();

// I1 - Registro válido
$id = $db->guardar("Producto Test", 20);
if ($id && $db->recuperar($id)['stock_minimo'] === 20) {
    echo "I1 PASS: Producto válido guardado y recuperado (stock 20)\n";
} else {
    echo "I1 FAIL\n";
}
$db->limpiar();

// I2 - Dato inválido
$idInvalido = $db->guardar("Producto Inválido", 3);
if ($idInvalido === false && $db->contar() === 0) {
    echo "I2 PASS: Dato inválido rechazado, no se creó registro\n";
} else {
    echo "I2 FAIL\n";
}
$db->limpiar();

echo "\nPruebas de integración finalizadas.\n";