SET NAMES utf8mb4;

INSERT INTO pedido_numeracion (fecha_dia, ultimo_numero)
VALUES (CURDATE(), 3);

INSERT INTO pedidos (numero_dia, fecha_dia, fecha_pedido, estado, tipo, metodo_pago, total, cliente_id, camarero_id)
VALUES
(1, CURDATE(), NOW(), 'recibido', 'local', 'camarero', 19.26, 2, NULL),
(2, CURDATE(), NOW(), 'listo_cocina', 'llevar', 'tarjeta', 6.06, 2, 4),
(3, CURDATE(), NOW(), 'terminado', 'local', 'tarjeta', 13.20, 2, 4);

INSERT INTO pedido_lineas (pedido_id, producto_id, producto_nombre, precio_base, iva, precio_final_unitario, cantidad, subtotal)
VALUES
(1, 1, 'Coca-Cola', 2.50, 21.00, 3.03, 2, 6.06),
(1, 2, 'Hamburguesa FDI', 12.00, 10.00, 13.20, 1, 13.20),
(2, 1, 'Coca-Cola', 2.50, 21.00, 3.03, 2, 6.06),
(3, 2, 'Hamburguesa FDI', 12.00, 10.00, 13.20, 1, 13.20);
