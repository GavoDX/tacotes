package com.tacotes.api

import retrofit2.http.*
import retrofit2.Response

data class Usuario(
    val id: Int,
    val nombre: String,
    val email: String,
    val token: String? = null
)

data class LoginRequest(
    val email: String,
    val password: String
)

data class RegistroRequest(
    val nombre: String,
    val email: String,
    val password: String,
    val telefono: String? = null,
    val direccion: String? = null
)

data class Producto(
    val id: Int,
    val nombre: String,
    val descripcion: String? = null,
    val precio: Double,
    val categoria: String,
    val imagen_url: String? = null,
    val stock: Int,
    val disponible: Boolean
)

data class Compra(
    val id: Int,
    val usuario_id: Int,
    val total: Double,
    val estado: String,
    val fecha_compra: String,
    val detalles: List<DetalleCompra>? = null
)

data class DetalleCompra(
    val id: Int,
    val producto_id: Int,
    val cantidad: Int,
    val precio_unitario: Double,
    val subtotal: Double,
    val producto_nombre: String? = null
)

data class ItemCompra(
    val producto_id: Int,
    val cantidad: Int
)

data class CrearCompraRequest(
    val usuario_id: Int,
    val items: List<ItemCompra>
)

data class ActualizarEstadoRequest(
    val estado: String
)

data class ApiResponse<T>(
    val exito: Boolean,
    val mensaje: String,
    val datos: T? = null
)

interface TacotesApiService {
    @POST("registro")
    suspend fun registro(@Body request: RegistroRequest): Response<ApiResponse<Usuario>>
    
    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<ApiResponse<Usuario>>
    
    @GET("productos")
    suspend fun listarProductos(
        @Query("categoria") categoria: String? = null,
        @Query("disponible") disponible: Boolean? = null
    ): Response<ApiResponse<List<Producto>>>
    
    @GET("productos")
    suspend fun obtenerProducto(
        @Query("accion") accion: String = "obtener",
        @Query("id") id: Int
    ): Response<ApiResponse<Producto>>
    
    @POST("productos")
    suspend fun crearProducto(
        @Query("accion") accion: String = "crear",
        @Body producto: Producto
    ): Response<ApiResponse<Map<String, Any>>>
    
    @PUT("productos")
    suspend fun actualizarProducto(
        @Query("accion") accion: String = "actualizar",
        @Query("id") id: Int,
        @Body producto: Producto
    ): Response<ApiResponse<String>>
    
    @DELETE("productos")
    suspend fun eliminarProducto(
        @Query("accion") accion: String = "eliminar",
        @Query("id") id: Int
    ): Response<ApiResponse<String>>
    
    @GET("compras")
    suspend fun listarCompras(
        @Query("usuario_id") usuarioId: Int,
        @Query("estado") estado: String? = null
    ): Response<ApiResponse<List<Compra>>>
    
    @GET("compras")
    suspend fun obtenerCompra(
        @Query("accion") accion: String = "obtener",
        @Query("id") id: Int
    ): Response<ApiResponse<Compra>>
    
    @POST("compras")
    suspend fun crearCompra(
        @Query("accion") accion: String = "crear",
        @Body request: CrearCompraRequest
    ): Response<ApiResponse<Map<String, Any>>>
    
    @PUT("compras")
    suspend fun actualizarEstadoCompra(
        @Query("accion") accion: String = "actualizar_estado",
        @Query("id") id: Int,
        @Body request: ActualizarEstadoRequest
    ): Response<ApiResponse<Map<String, Any>>>
    
    @DELETE("compras")
    suspend fun cancelarCompra(
        @Query("accion") accion: String = "cancelar",
        @Query("id") id: Int
    ): Response<ApiResponse<String>>
}
