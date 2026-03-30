SET NAMES utf8mb4;

INSERT INTO pedido_numeracion (fecha_dia, ultimo_numero)
VALUES (CURDATE(), 5);

INSERT INTO pedidos (numero_dia, fecha_dia, fecha_pedido, estado, tipo, metodo_pago, total, cliente_id, camarero_id, cocinero_id)
VALUES
(1, CURDATE(), NOW(), 'recibido', 'local', 'camarero', 19.26, 2, NULL, NULL),
(2, CURDATE(), NOW(), 'listo_cocina', 'llevar', 'tarjeta', 6.06, 2, 4, 3),
(3, CURDATE(), NOW(), 'terminado', 'local', 'tarjeta', 13.20, 2, 4, 3),
(4, CURDATE(), NOW(), 'en_preparacion', 'local', 'tarjeta', 19.26, 2, NULL, NULL),
(5, CURDATE(), NOW(), 'cocinando', 'llevar', 'tarjeta', 19.26, 2, NULL, 3);

INSERT INTO pedido_lineas (pedido_id, producto_id, producto_nombre, precio_base, iva, precio_final_unitario, cantidad, subtotal, preparado)
VALUES

(1, 1, 'Nuka Cola', 2.50, 21.00, 3.03, 2, 6.06, 0),
(1, 2, 'Hamburguesa FDI', 12.00, 10.00, 13.20, 1, 13.20, 0),
(2, 1, 'Nuka Cola', 2.50, 21.00, 3.03, 2, 6.06, 1),
(3, 2, 'Hamburguesa FDI', 12.00, 10.00, 13.20, 1, 13.20, 1),
(4, 1, 'Nuka Cola', 2.50, 21.00, 3.03, 2, 6.06, 0),
(4, 2, 'Hamburguesa FDI', 12.00, 10.00, 13.20, 1, 13.20, 0),
(5, 1, 'Nuka Cola', 2.50, 21.00, 3.03, 2, 6.06, 1),
(5, 2, 'Hamburguesa FDI', 12.00, 10.00, 13.20, 1, 13.20, 0);
