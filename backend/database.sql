-- ============================================
-- Base de datos: railway (PostgreSQL)
-- Proyecto: Tacotes
-- ============================================

DROP TABLE IF EXISTS productos CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    celular VARCHAR(20) UNIQUE NOT NULL,
    clave VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos (tacos guardados por cada usuario)
CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    taqueria VARCHAR(100),
    pais_origen VARCHAR(50),
    telefono VARCHAR(20),
    nivel_picante VARCHAR(50),
    tipo_tortilla VARCHAR(20) CHECK (tipo_tortilla IN ('Maíz','Harina','Azul','Integral')),
    variedad_carne VARCHAR(20) CHECK (variedad_carne IN ('Pastor','Asada','Carnitas','Barbacoa','Birria','Suadero')),
    perfil_sabor VARCHAR(20) CHECK (perfil_sabor IN ('Picante','Ahumado','Salado','Jugoso','Especiado')),
    categoria VARCHAR(30) CHECK (categoria IN ('Taco Tradicional','Taco Gourmet','Taco Vegano','Taco de Mariscos','Taco Regional')),
    porcion VARCHAR(20) CHECK (porcion IN ('1 Taco','3 Tacos','5 Tacos','Orden Completa')),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Datos de prueba
-- Usuario admin, clave: 123456
INSERT INTO usuarios (usuario, celular, clave) VALUES
('admin', '3311223344', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO productos (usuario_id, nombre, taqueria, pais_origen, telefono, nivel_picante, tipo_tortilla, variedad_carne, perfil_sabor, categoria, porcion) VALUES
(1, 'Taco al Pastor', 'El Califa', 'México', '3312345678', 'Medio', 'Maíz', 'Pastor', 'Especiado', 'Taco Tradicional', '3 Tacos'),
(1, 'Taco de Birria', 'Birriería Don Beto', 'México', '3398765432', 'Alto', 'Maíz', 'Birria', 'Jugoso', 'Taco Regional', '5 Tacos');
