-- ============================================
-- Base de datos: railway (PostgreSQL)
-- Proyecto: Tacotes API
-- ============================================

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    celular VARCHAR(20) UNIQUE NOT NULL,
    clave VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos (tacos)
CREATE TABLE IF NOT EXISTS productos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    taqueria VARCHAR(100),
    pais_origen VARCHAR(50),
    telefono VARCHAR(20),
    nivel_picante VARCHAR(50),
    tipo_tortilla VARCHAR(50),
    variedad_carne VARCHAR(50),
    perfil_sabor VARCHAR(100),
    categoria VARCHAR(50),
    porcion VARCHAR(50),
    precio DECIMAL(10, 2) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de compras (carrito)
CREATE TABLE IF NOT EXISTS compras (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER NOT NULL,
    producto_id INTEGER NOT NULL,
    cantidad INTEGER NOT NULL DEFAULT 1,
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);
