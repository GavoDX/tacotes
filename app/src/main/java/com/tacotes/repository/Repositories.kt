package com.tacotes.repository

import com.tacotes.api.ApiClient
import com.tacotes.api.*
import android.util.Log

class UsuarioRepository {
    private val apiService = ApiClient.apiService
    private val TAG = "UsuarioRepository"
    
    var usuarioActual: Usuario? = null
    var token: String? = null
    
    suspend fun registro(nombre: String, email: String, password: String, telefono: String = "", direccion: String = ""): Result<Usuario> {
        return try {
            val request = RegistroRequest(nombre, email, password, telefono, direccion)
            val response = apiService.registro(request)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                val usuario = response.body()?.datos
                if (usuario != null) {
                    Result.success(usuario)
                } else {
                    Result.failure(Exception("Usuario no recibido"))
                }
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error en registro"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error en registro", e)
            Result.failure(e)
        }
    }
    
    suspend fun login(email: String, password: String): Result<Usuario> {
        return try {
            val request = LoginRequest(email, password)
            val response = apiService.login(request)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                val usuario = response.body()?.datos
                if (usuario != null) {
                    usuarioActual = usuario
                    token = usuario.token
                    Result.success(usuario)
                } else {
                    Result.failure(Exception("Usuario no recibido"))
                }
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error en login"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error en login", e)
            Result.failure(e)
        }
    }
    
    fun logout() {
        usuarioActual = null
        token = null
    }
    
    fun isLogged(): Boolean = usuarioActual != null && !token.isNullOrEmpty()
}

class ProductoRepository {
    private val apiService = ApiClient.apiService
    private val TAG = "ProductoRepository"
    
    suspend fun listarProductos(categoria: String? = null): Result<List<Producto>> {
        return try {
            val response = apiService.listarProductos(categoria = categoria, disponible = true)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                val productos = response.body()?.datos ?: emptyList()
                Result.success(productos)
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error al listar productos"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error al listar productos", e)
            Result.failure(e)
        }
    }
    
    suspend fun obtenerProducto(id: Int): Result<Producto> {
        return try {
            val response = apiService.obtenerProducto(id = id)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                val producto = response.body()?.datos
                if (producto != null) {
                    Result.success(producto)
                } else {
                    Result.failure(Exception("Producto no encontrado"))
                }
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error al obtener producto"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error al obtener producto", e)
            Result.failure(e)
        }
    }
}

class CompraRepository {
    private val apiService = ApiClient.apiService
    private val TAG = "CompraRepository"
    
    suspend fun listarCompras(usuarioId: Int, estado: String? = null): Result<List<Compra>> {
        return try {
            val response = apiService.listarCompras(usuarioId, estado)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                val compras = response.body()?.datos ?: emptyList()
                Result.success(compras)
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error al listar compras"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error al listar compras", e)
            Result.failure(e)
        }
    }
    
    suspend fun obtenerCompra(id: Int): Result<Compra> {
        return try {
            val response = apiService.obtenerCompra(id = id)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                val compra = response.body()?.datos
                if (compra != null) {
                    Result.success(compra)
                } else {
                    Result.failure(Exception("Compra no encontrada"))
                }
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error al obtener compra"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error al obtener compra", e)
            Result.failure(e)
        }
    }
    
    suspend fun crearCompra(usuarioId: Int, items: List<ItemCompra>): Result<Int> {
        return try {
            val request = CrearCompraRequest(usuarioId, items)
            val response = apiService.crearCompra(request = request)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                val compraId = response.body()?.datos?.get("compra_id") as? Number
                if (compraId != null) {
                    Result.success(compraId.toInt())
                } else {
                    Result.failure(Exception("ID de compra no recibido"))
                }
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error al crear compra"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error al crear compra", e)
            Result.failure(e)
        }
    }
    
    suspend fun actualizarEstado(compraId: Int, estado: String): Result<String> {
        return try {
            val request = ActualizarEstadoRequest(estado)
            val response = apiService.actualizarEstadoCompra(id = compraId, request = request)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                Result.success(response.body()?.mensaje ?: "Estado actualizado")
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error al actualizar estado"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error al actualizar estado", e)
            Result.failure(e)
        }
    }
    
    suspend fun cancelarCompra(compraId: Int): Result<String> {
        return try {
            val response = apiService.cancelarCompra(id = compraId)
            
            if (response.isSuccessful && response.body()?.exito == true) {
                Result.success(response.body()?.mensaje ?: "Compra cancelada")
            } else {
                Result.failure(Exception(response.body()?.mensaje ?: "Error al cancelar compra"))
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error al cancelar compra", e)
            Result.failure(e)
        }
    }
}
