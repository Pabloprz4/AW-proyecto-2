INSERT INTO categorias (id, nombre, descripcion) VALUES
(1, 'Bebidas', 'Refrescos, cervezas y agua'),
(2, 'Principales', 'Platos fuertes y hamburguesas'),
(3, 'Postres', 'Dulces y helados');

INSERT INTO productos (id, categoria_id, nombre, descripcion, precio, iva, foto, disponible, ofertado) VALUES
(1, 1, 'Nuka Cola', 'Lata de 33cl', 2.50, 21.00, 'img/nuka_cola.jpg', 1, 1),
(2, 2, 'Hamburguesa FDI', 'Doble carne con queso y bacon', 12.00, 10.00, 'img/hamburguesa.jpg', 1, 1),
(3, 3, 'Tarta de Queso', 'Casera con mermelada', 5.50, 10.00, 'img/tarta.jpeg', 1, 1);

INSERT INTO producto_imagenes (producto_id, ruta, orden) VALUES
(1, 'img/nuka_cola.jpg', 1),
(2, 'img/hamburguesa.jpg', 1),
(2, 'img/hamburguesa_detalle.png', 2),
(3, 'img/tarta.jpeg', 1);
