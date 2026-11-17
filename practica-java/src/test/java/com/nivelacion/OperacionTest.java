package com.nivelacion;

import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.assertEquals;

public class OperacionTest {

    @Test
    public void testRestaAsignada() {
        // 150 - 65 = 85
        assertEquals(85, Operacion.restar(150, 65));
    }

    @Test
    public void testRestaAdicional() {
        // Caso adicional
        assertEquals(7, Operacion.restar(10, 3));
    }
}