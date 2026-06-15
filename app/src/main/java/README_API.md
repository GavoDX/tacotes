# Guía de Conexión API - Tacotes App

## Configuración

### 1. Actualizar URL del Servidor
En el archivo `ApiClient.kt`, actualiza la URL base:

```kotlin
private const val BASE_URL = "http://TU_IP_SERVIDOR/tacotes/backend/"
```

Reemplaza `TU_IP_SERVIDOR` con:
- Tu IP local si está en desarrollo: `192.168.x.x`
- Tu dominio o IP pública si está en producción

### 2. Permisos de Internet
Ya están configurados en `AndroidManifest.xml`:
```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
```

## Clases Principales

### ApiClient.kt
Maneja la conexión HTTP con Retrofit y OkHttp.

### ApiService.kt
Define los endpoints y data classes para:
- Login/Registro
- Obtener productos
- Obtener/agregar/eliminar compras

### TacotesRepository.kt
Capa de abstracción para llamadas a la API con manejo de errores.

### AuthManager.kt
Gestiona las sesiones de usuario con SharedPreferences.

## Uso en Activities/Fragments

```kotlin
import kotlinx.coroutines.*

class LoginActivity : AppCompatActivity() {
    private val apiService = ApiClient.instance.create(ApiService::class.java)
    private val repository = TacotesRepository(apiService)
    private val authManager by lazy { AuthManager(this) }
    
    private fun loginUser(usuario: String, clave: String) {
        lifecycleScope.launch {
            val response = repository.login(usuario, clave)
            if (response.success && response.usuario_id != null) {
                authManager.saveUserSession(response.usuario_id, response.usuario ?: usuario)
                startActivity(Intent(this@LoginActivity, MainActivity::class.java))
                finish()
            } else {
                Toast.makeText(this@LoginActivity, response.message, Toast.LENGTH_SHORT).show()
            }
        }
    }
}
```

## Endpoints Disponibles

### POST /login.php
```json
{
    "usuario": "user123",
    "clave": "password123"
}
```

### POST /registro.php
```json
{
    "usuario": "user123",
    "clave": "password123",
    "email": "user@example.com"
}
```

### GET /productos.php
Retorna todos los productos disponibles.

### GET /compras.php?usuario_id=1
Retorna las compras del usuario.

### POST /agregar_compra.php
```json
{
    "usuario_id": 1,
    "producto_id": 5,
    "cantidad": 2
}
```

### POST /eliminar_compra.php
```json
{
    "compra_id": 10
}
```

## Solución de Problemas

### "No se puede conectar al servidor"
- Verifica que el servidor PHP esté funcionando
- Verifica la URL en ApiClient.kt
- En Android Studio, usa `10.0.2.2` si emulas en localhost

### "Connection timed out"
- Aumenta los timeouts en ApiClient.kt
- Verifica la conexión de red del dispositivo

### "JSON parse error"
- Verifica que el servidor devuelva JSON válido
- Revisa el formato de los data classes
