SET NAMES utf8mb4;

DROP TABLE IF EXISTS pedido_lineas;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS pedido_numeracion;

CREATE TABLE pedido_numeracion (
  fecha_dia DATE PRIMARY KEY,
  ultimo_numero INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedidos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  numero_dia INT UNSIGNED NOT NULL,
  fecha_dia DATE NOT NULL,
  fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('recibido','en_preparacion','cocinando','listo_cocina','terminado','entregado') NOT NULL DEFAULT 'recibido',
  tipo ENUM('local','llevar') NOT NULL,
  metodo_pago ENUM('tarjeta','camarero') NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  cliente_id INT UNSIGNED NOT NULL,
  camarero_id INT UNSIGNED DEFAULT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_pedidos_numero_dia (fecha_dia, numero_dia),
  KEY idx_pedidos_cliente (cliente_id),
  KEY idx_pedidos_estado (estado),
  KEY idx_pedidos_camarero (camarero_id),
  CONSTRAINT fk_pedidos_cliente FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
  CONSTRAINT fk_pedidos_camarero FOREIGN KEY (camarero_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedido_lineas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NOT NULL,
  producto_nombre VARCHAR(100) NOT NULL,
  precio_base DECIMAL(10,2) NOT NULL,
  iva DECIMAL(5,2) NOT NULL,
  precio_final_unitario DECIMAL(10,2) NOT NULL,
  cantidad INT UNSIGNED NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_pedido_lineas_pedido (pedido_id),
  KEY idx_pedido_lineas_producto (producto_id),
  CONSTRAINT fk_pedido_lineas_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
  CONSTRAINT fk_pedido_lineas_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
