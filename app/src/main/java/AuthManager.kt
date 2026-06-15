import android.content.Context
import android.content.SharedPreferences

class AuthManager(context: Context) {
    private val prefs: SharedPreferences = context.getSharedPreferences(
        "tacotes_prefs",
        Context.MODE_PRIVATE
    )
    
    companion object {
        private const val KEY_USUARIO_ID = "usuario_id"
        private const val KEY_USUARIO_NAME = "usuario_name"
        private const val KEY_IS_LOGGED_IN = "is_logged_in"
    }
    
    fun saveUserSession(usuarioId: Int, usuarioName: String) {
        prefs.edit().apply {
            putInt(KEY_USUARIO_ID, usuarioId)
            putString(KEY_USUARIO_NAME, usuarioName)
            putBoolean(KEY_IS_LOGGED_IN, true)
            apply()
        }
    }
    
    fun getUserId(): Int? {
        val id = prefs.getInt(KEY_USUARIO_ID, -1)
        return if (id != -1) id else null
    }
    
    fun getUserName(): String? {
        return prefs.getString(KEY_USUARIO_NAME, null)
    }
    
    fun isLoggedIn(): Boolean {
        return prefs.getBoolean(KEY_IS_LOGGED_IN, false)
    }
    
    fun logout() {
        prefs.edit().apply {
            clear()
            apply()
        }
    }
}
