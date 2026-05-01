INSERT INTO categorias (id, nombre, descripcion) VALUES
(1, 'Bebidas', 'Refrescos, cervezas y agua'),
(2, 'Principales', 'Platos fuertes y hamburguesas'),
(3, 'Postres', 'Dulces y helados');

INSERT INTO productos (id, categoria_id, nombre, descripcion, precio, iva, foto, disponible, ofertado) VALUES
(1, 1, 'Nuka Cola', 'Lata de 33cl', 2.50, 21.00, 'img/nuka_cola.jpg', 1, 1),
(2, 2, 'Hamburguesa FDI', 'Doble carne con queso y bacon', 12.00, 10.00, 'img/hamburguesa.jpg', 1, 1),
(3, 3, 'Tarta de Queso', 'Casera con mermelada', 5.50, 10.00, 'img/tarta.jpeg', 1, 1),
(4, 1, 'Milkshake', 'Batido frio con nata', 4.20, 10.00, 'img/milkshake.jpg', 1, 1),
(5, 1, 'Agua Mineral', 'Botella de agua de 50cl', 1.60, 10.00, 'img/agua_mineral.jpg', 1, 1),
(6, 1, 'Cafe Vault', 'Cafe caliente recien hecho', 1.80, 10.00, 'img/cafe_vault.jpeg', 1, 1),
(7, 2, 'Papas Fritas', 'Racion de papas fritas crujientes', 3.80, 10.00, 'img/papas_fritas.jpg', 1, 1),
(8, 2, 'Pizza del Yermo', 'Pizza individual con queso y pepperoni', 9.50, 10.00, 'img/pizza_del_yermo.jpg', 1, 1),
(9, 2, 'Sandwich Club', 'Sandwich tostado con pollo, bacon y lechuga', 7.50, 10.00, 'img/sandwich_club.jpg', 1, 1),
(10, 3, 'Brownie Rad', 'Brownie de chocolate con nueces', 4.80, 10.00, 'img/brownie_rad.jpg', 1, 1),
(11, 3, 'Helado de Vainilla', 'Copa de helado de vainilla', 3.90, 10.00, 'img/helado_vainilla.jpg', 1, 1),
(12, 3, 'Donut Homero', 'Donut rosa con glaseado', 2.80, 10.00, 'img/donut_homero.jpg', 1, 1);

INSERT INTO producto_imagenes (producto_id, ruta, orden) VALUES
(1, 'img/nuka_cola.jpg', 1),
(2, 'img/hamburguesa.jpg', 1),
(2, 'img/hamburguesa_detalle.png', 2),
(3, 'img/tarta.jpeg', 1),
(4, 'img/milkshake.jpg', 1),
(5, 'img/agua_mineral.jpg', 1),
(6, 'img/cafe_vault.jpeg', 1),
(7, 'img/papas_fritas.jpg', 1),
(8, 'img/pizza_del_yermo.jpg', 1),
(9, 'img/sandwich_club.jpg', 1),
(10, 'img/brownie_rad.jpg', 1),
(11, 'img/helado_vainilla.jpg', 1),
(12, 'img/donut_homero.jpg', 1);
