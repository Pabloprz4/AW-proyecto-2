SET NAMES utf8mb4;

INSERT INTO usuarios (nombre_usuario, email, nombre, apellidos, password_hash, rol, avatar, activo) VALUES
('gerente',  'gerente@bistrofdi.local',  'Usuario', 'Gerente',  '$2y$10$m4KfB6eWXp78UGUn61vMt.CPU.ADhoMLrmZwOpd2XTqKI1uBqluu6', 'gerente',  NULL, 1),
('cliente1', 'cliente1@bistrofdi.local', 'Cliente', 'Demo',     '$2y$10$OvMuIPq2hrCb9C666OcT7.1/1eXLaeJ4nDCRiUTWErte864k3p85G', 'cliente',  NULL, 1),
('cocinero', 'cocinero@bistrofdi.local', 'Cocinero', 'Demo',    '$2y$10$IGrkzflo6xH8sdupYhyj7.XmWTVy4imHzlMLM/lKnpQr07vQYwmla', 'cocinero', NULL, 1),
('camarero', 'camarero@bistrofdi.local', 'Camarero', 'Demo',    '$2y$10$3N8xj9my9aT1JBMs7c1pmuTUfh4OSZjbQTOv.DZRyHwPBNkBpgZXW', 'camarero', NULL, 1);
