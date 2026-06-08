package com.example.chocolate

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.Menu
import android.view.MenuItem
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.Toolbar
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

class Creador : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_creador)

        val toolbar = findViewById<Toolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }
    }

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menu, menu)

        val preferences = getSharedPreferences("MisDatos", Context.MODE_PRIVATE)
        val esAdmin = preferences.getBoolean("esAdmin", false)

        if (!esAdmin) {
            menu?.findItem(R.id.opc1)?.isVisible = false
            menu?.findItem(R.id.opc3)?.isVisible = false
            menu?.findItem(R.id.opc4)?.isVisible = false
        }

        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        if (item.getItemId() == R.id.opc1) {
            val cambio = Intent(this, MainActivity::class.java)
            startActivity(cambio)
        }
        if (item.getItemId() == R.id.opc2) {
            val cambio = Intent(this, Ver::class.java)
            startActivity(cambio)
        }
        if (item.getItemId() == R.id.opc3) {
            val cambio = Intent(this, Actualizar::class.java)
            startActivity(cambio)
        }
        if (item.getItemId() == R.id.opc4) {
            val cambio = Intent(this, Eliminar::class.java)
            startActivity(cambio)
        }
        if (item.getItemId() == R.id.opc5) {
            val preferences = getSharedPreferences("MisDatos", Context.MODE_PRIVATE)
            val editor = preferences.edit()
            editor.putBoolean("logeado", false)
            editor.apply()

            val intent = Intent(this, Sesion::class.java)
            startActivity(intent)
            finishAffinity()
        }
        if (item.getItemId() == R.id.opc6) {
            val cambio = Intent(this, Contacto::class.java)
            startActivity(cambio)
        }
        if (item.getItemId() == R.id.opc7) {
            Toast.makeText(this, "Ya estás en la opción", Toast.LENGTH_LONG).show()
        }
        return super.onOptionsItemSelected(item)
    }
}