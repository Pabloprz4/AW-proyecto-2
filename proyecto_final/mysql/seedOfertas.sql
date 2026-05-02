SET NAMES utf8mb4;

-- Insertar oferta de ejemplo: Desayuno Andaluz
INSERT INTO ofertas (nombre, descripcion, descuento, fecha_inicio, fecha_fin, activo) VALUES
('Desayuno Andaluz', 'Café con tostada de aceite y tomate', 21.50, '2024-01-01', '2024-12-31', 1);

-- Obtener ID de la oferta
SET @oferta_id = LAST_INSERT_ID();

-- Insertar productos para la oferta (asumiendo IDs de productos existentes)
-- Café ID 1, Tostada ID 2
INSERT INTO oferta_productos (oferta_id, producto_id, cantidad) VALUES
(@oferta_id, 1, 1), -- Café
(@oferta_id, 2, 1); -- Tostada