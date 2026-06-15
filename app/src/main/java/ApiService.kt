import retrofit2.http.*
import retrofit2.Response

interface ApiService {
    @POST("login.php")
    suspend fun login(@Body loginData: LoginRequest): LoginResponse
    
    @POST("registro.php")
    suspend fun registro(@Body registroData: RegistroRequest): RegistroResponse
    
    @GET("productos.php")
    suspend fun getProductos(): ProductosResponse
    
    @GET("compras.php")
    suspend fun getCompras(@Query("usuario_id") usuarioId: Int): ComprasResponse
    
    @POST("agregar_compra.php")
    suspend fun agregarCompra(@Body compraData: AgregarCompraRequest): ApiResponse
    
    @POST("eliminar_compra.php")
    suspend fun eliminarCompra(@Body eliminarData: EliminarCompraRequest): ApiResponse
}

// Data classes
data class LoginRequest(
    val usuario: String,
    val clave: String
)

data class LoginResponse(
    val success: Boolean,
    val message: String,
    val usuario_id: Int? = null,
    val usuario: String? = null
)

data class RegistroRequest(
    val usuario: String,
    val clave: String,
    val email: String? = null
)

data class RegistroResponse(
    val success: Boolean,
    val message: String
)

data class ProductosResponse(
    val success: Boolean,
    val productos: List<Producto>? = null,
    val message: String? = null
)

data class Producto(
    val id: Int,
    val nombre: String,
    val taqueria: String,
    val precio: Double,
    val variedad_carne: String,
    val tipo_tortilla: String,
    val nivel_picante: Int,
    val categoria: String,
    val porcion: String
)

data class ComprasResponse(
    val success: Boolean,
    val compras: List<Compra>? = null,
    val total: Double? = null,
    val message: String? = null
)

data class Compra(
    val id: Int,
    val producto_id: Int,
    val nombre: String,
    val taqueria: String,
    val precio: Double,
    val cantidad: Int,
    val total: Double,
    val fecha_compra: String
)

data class AgregarCompraRequest(
    val usuario_id: Int,
    val producto_id: Int,
    val cantidad: Int = 1
)

data class EliminarCompraRequest(
    val compra_id: Int
)

data class ApiResponse(
    val success: Boolean,
    val message: String
)
