# TACOTES 🌮

Sistema de compra y venta de tacos con Android app y backend PHP.

## Características

✅ **Sistema de Usuarios**
- Registro seguro con validación
- Login con hasheo de contraseña BCRYPT
- Gestión de perfiles

✅ **Catálogo de Productos**
- Listar tacos y bebidas
- Categorías de productos
- Información de stock en tiempo real

✅ **Sistema de Compras**
- Carrito de compra
- Gestión de estados (pendiente, confirmada, en_preparacion, lista, entregada)
- Cálculo automático de totales
- Control transaccional de stock

## Estructura del Proyecto

```
tacotes/
├── app/              # Aplicación Android (Kotlin)
├── backend/          # API REST (PHP)
│   ├── config.php    # Configuración de BD y helpers
│   ├── index.php     # Router de API
│   ├── registro.php  # Endpoint de registro
│   ├── login.php     # Endpoint de login
│   ├── productos.php # CRUD de productos
│   ├── compras.php   # Gestión de compras
│   └── database.sql  # Esquema de base de datos
└── README.md
```

## Setup

### Base de Datos

1. Crear base de datos:
```sql
CREATE DATABASE tacotes_db;
USE tacotes_db;
```

2. Importar esquema:
```bash
mysql -u root < backend/database.sql
```

### API REST

1. Configurar en `backend/config.php`:
   - DB_HOST
   - DB_USER
   - DB_PASS
   - DB_NAME

2. Endpoints disponibles:

#### Usuarios
- `POST /registro` - Registrar usuario
- `POST /login` - Iniciar sesión

#### Productos
- `GET /productos` - Listar productos
- `GET /productos?accion=obtener&id=1` - Obtener producto
- `POST /productos?accion=crear` - Crear producto
- `PUT /productos?accion=actualizar&id=1` - Actualizar producto
- `DELETE /productos?accion=eliminar&id=1` - Eliminar producto

#### Compras
- `GET /compras?usuario_id=1` - Listar compras
- `GET /compras?accion=obtener&id=1` - Obtener compra
- `POST /compras?accion=crear` - Crear compra
- `PUT /compras?accion=actualizar_estado&id=1` - Actualizar estado
- `DELETE /compras?accion=cancelar&id=1` - Cancelar compra

### Android App

1. Actualizar URL de API en la app
2. Compilar y ejecutar

## Ejemplos de uso

### Registro
```bash
curl -X POST http://localhost/tacotes/backend/registro \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan Pérez",
    "email": "juan@example.com",
    "password": "123456",
    "telefono": "555-1234",
    "direccion": "Calle 123"
  }'
```

### Login
```bash
curl -X POST http://localhost/tacotes/backend/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@example.com",
    "password": "123456"
  }'
```

### Crear Compra
```bash
curl -X POST http://localhost/tacotes/backend/compras?accion=crear \
  -H "Content-Type: application/json" \
  -d '{
    "usuario_id": 1,
    "items": [
      {"producto_id": 1, "cantidad": 2},
      {"producto_id": 3, "cantidad": 1}
    ]
  }'
```

## Tecnologías

- **Backend**: PHP 7.4+, MySQL
- **Frontend**: Kotlin (Android)
- **Autenticación**: BCRYPT password hashing
- **API**: REST JSON

## Licencia

MIT
