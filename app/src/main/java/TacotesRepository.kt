import android.util.Log

class TacotesRepository(private val apiService: ApiService) {
    
    companion object {
        private const val TAG = "TacotesRepository"
    }
    
    suspend fun login(usuario: String, clave: String): LoginResponse {
        return try {
            val response = apiService.login(LoginRequest(usuario, clave))
            Log.d(TAG, "Login response: $response")
            response
        } catch (e: Exception) {
            Log.e(TAG, "Error en login: ${e.message}")
            LoginResponse(false, "Error de conexión: ${e.message}")
        }
    }
    
    suspend fun registro(usuario: String, clave: String, email: String? = null): RegistroResponse {
        return try {
            val response = apiService.registro(RegistroRequest(usuario, clave, email))
            Log.d(TAG, "Registro response: $response")
            response
        } catch (e: Exception) {
            Log.e(TAG, "Error en registro: ${e.message}")
            RegistroResponse(false, "Error de conexión: ${e.message}")
        }
    }
    
    suspend fun getProductos(): ProductosResponse {
        return try {
            val response = apiService.getProductos()
            Log.d(TAG, "Productos obtenidos: ${response.productos?.size}")
            response
        } catch (e: Exception) {
            Log.e(TAG, "Error obteniendo productos: ${e.message}")
            ProductosResponse(false, null, "Error de conexión: ${e.message}")
        }
    }
    
    suspend fun getCompras(usuarioId: Int): ComprasResponse {
        return try {
            val response = apiService.getCompras(usuarioId)
            Log.d(TAG, "Compras obtenidas: ${response.compras?.size}")
            response
        } catch (e: Exception) {
            Log.e(TAG, "Error obteniendo compras: ${e.message}")
            ComprasResponse(false, null, null, "Error de conexión: ${e.message}")
        }
    }
    
    suspend fun agregarCompra(usuarioId: Int, productoId: Int, cantidad: Int = 1): ApiResponse {
        return try {
            val response = apiService.agregarCompra(
                AgregarCompraRequest(usuarioId, productoId, cantidad)
            )
            Log.d(TAG, "Compra agregada: ${response.message}")
            response
        } catch (e: Exception) {
            Log.e(TAG, "Error agregando compra: ${e.message}")
            ApiResponse(false, "Error de conexión: ${e.message}")
        }
    }
    
    suspend fun eliminarCompra(compraId: Int): ApiResponse {
        return try {
            val response = apiService.eliminarCompra(EliminarCompraRequest(compraId))
            Log.d(TAG, "Compra eliminada: ${response.message}")
            response
        } catch (e: Exception) {
            Log.e(TAG, "Error eliminando compra: ${e.message}")
            ApiResponse(false, "Error de conexión: ${e.message}")
        }
    }
}
