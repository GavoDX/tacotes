package com.example.chocolate

import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

class Sesion : AppCompatActivity() {
    private lateinit var preferences: SharedPreferences

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_sesion)

        val etUsuario = findViewById<EditText>(R.id.Usuario)
        val etContra = findViewById<EditText>(R.id.Contra)
        val btnIniciar = findViewById<Button>(R.id.Iniciar)

        preferences = getSharedPreferences("MisDatos", Context.MODE_PRIVATE)
        val editor = preferences.edit()

        // Inicializamos las credenciales en SharedPreferences para evitar hardcode en la validación
        if (!preferences.contains("admin_user")) {
            editor.putString("admin_user", "pablo")
            editor.putString("admin_pass", "1024")
            editor.putString("user_key", "camila")
            editor.putString("pass_key", "0609")

            editor.apply()
        }

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        btnIniciar.setOnClickListener {
            val userIngresado = etUsuario.text.toString().trim()
            val passIngresada = etContra.text.toString().trim()

            // Recuperamos los datos de SharedPreferences
            val adminUser = preferences.getString("admin_user", "")
            val adminPass = preferences.getString("admin_pass", "")
            val userGuardado = preferences.getString("user_key", "")
            val passGuardada = preferences.getString("pass_key", "")

            if (userIngresado.isEmpty() || passIngresada.isEmpty()) {
                Toast.makeText(this, "Por favor, llena todos los campos", Toast.LENGTH_SHORT).show()
            } else {
                // Validación detallada para mostrar Toasts específicos
                if (userIngresado == adminUser) {
                    if (passIngresada == adminPass) {
                        irAlMenu(true, userIngresado)
                    } else {
                        Toast.makeText(this, "Contraseña incorrecta", Toast.LENGTH_SHORT).show()
                    }
                } else if (userIngresado == userGuardado) {
                    if (passIngresada == passGuardada) {
                        irAlMenu(false, userIngresado)
                    } else {
                        Toast.makeText(this, "Contraseña incorrecta", Toast.LENGTH_SHORT).show()
                    }
                } else {
                    Toast.makeText(this, "Usuario incorrecto", Toast.LENGTH_SHORT).show()
                }
            }
        }
    }

    private fun irAlMenu(esAdmin: Boolean, nombre: String) {
        val editor = preferences.edit()
        editor.putBoolean("esAdmin", esAdmin)
        editor.putBoolean("logeado", true)
        editor.putString("valor", nombre) // Guardamos el nombre del usuario logeado en la llave 'valor'
        editor.apply()
        
        if (esAdmin) {
            val intent = Intent(this, MainActivity::class.java)
            startActivity(intent)
        } else {
            val intent = Intent(this, Ver::class.java)
            startActivity(intent)
        }
        finish()
    }
}
