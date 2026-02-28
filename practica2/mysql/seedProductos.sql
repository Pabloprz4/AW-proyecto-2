INSERT INTO categorias (id, nombre, descripcion) VALUES
(1, 'Bebidas', 'Refrescos, cervezas y agua'),
(2, 'Principales', 'Platos fuertes y hamburguesas'),
(3, 'Postres', 'Dulces y helados');

INSERT INTO productos (categoria_id, nombre, descripcion, precio, iva, foto, ofertado) VALUES
(1, 'Coca-Cola', 'Lata de 33cl', 2.50, 21.00, 'img/cocacola.png', 1),
(2, 'Hamburguesa FDI', 'Doble carne con queso y bacon', 12.00, 10.00, 'img/hamburguesa.jpg', 1),
(3, 'Tarta de Queso', 'Casera con mermelada', 5.50, 10.00, 'img/tarta.jpg', 1);
