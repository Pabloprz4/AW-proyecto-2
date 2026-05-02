SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
-- Importacion segura: borrar primero tablas hijas y luego padres.
DROP TABLE IF EXISTS oferta_productos;
DROP TABLE IF EXISTS ofertas;

-- TABLA DE OFERTAS
CREATE TABLE ofertas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  descuento DECIMAL(5, 2) NOT NULL, -- Porcentaje de descuento (ej. 21.50)
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA DE PRODUCTOS EN OFERTA (1..N por oferta)
CREATE TABLE oferta_productos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  oferta_id INT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NOT NULL,
  cantidad INT UNSIGNED NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_oferta_productos_oferta (oferta_id),
  KEY idx_oferta_productos_producto (producto_id),
  CONSTRAINT fk_oferta_productos_oferta
    FOREIGN KEY (oferta_id) REFERENCES ofertas(id) ON DELETE CASCADE,
  CONSTRAINT fk_oferta_productos_producto
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;