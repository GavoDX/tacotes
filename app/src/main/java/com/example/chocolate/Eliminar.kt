package com.example.chocolate

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.Menu
import android.view.MenuItem
import android.widget.Button
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.appcompat.widget.Toolbar

class Eliminar : AppCompatActivity() {

    private lateinit var recy: RecyclerView
    private lateinit var adapter: Eliminaadapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_eliminar)

        if (ListaChocolate.listaChocolates.isEmpty()) {
            Toast.makeText(this, "No se han creado chocolates", Toast.LENGTH_SHORT).show()
        }

        val toolbar = findViewById<Toolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)

        recy = findViewById(R.id.rv)
        recy.layoutManager = LinearLayoutManager(this)

        adapter = Eliminaadapter(ListaChocolate.listaChocolates)
        recy.adapter = adapter

        val btnElimina = findViewById<Button>(R.id.elimina)
        btnElimina.setOnClickListener {
            eliminar()
        }

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }
    }

    private fun eliminar() {
        val seleccionados = adapter.itemsSeleccionados

        if (seleccionados.isEmpty()) {
            Toast.makeText(this, "Por favor, selecciona algún chocolate", Toast.LENGTH_SHORT).show()
        } else {
            ListaChocolate.listaChocolates.removeAll(seleccionados)
            seleccionados.clear()
            adapter.notifyDataSetChanged()

            Toast.makeText(this, "Chocolates eliminados correctamente", Toast.LENGTH_SHORT).show()
        }
    }

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menu, menu)
        return super.onCreateOptionsMenu(menu)
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        if (item.getItemId()==R.id.opc1)
        {
            val cambio = Intent(this, MainActivity::class.java)
            startActivity(cambio)
        }
        if(item.getItemId()==R.id.opc2)
        {
            val cambio = Intent(this, Ver::class.java)
            startActivity(cambio)
        }
        if(item.getItemId()==R.id.opc3)
        {
            val cambio = Intent(this, Actualizar::class.java)
            startActivity(cambio)
        }
        if(item.getItemId()==R.id.opc4)
        {
            Toast.makeText(this, "Ya estás en la opción", Toast.LENGTH_LONG).show()
        }
        if(item.getItemId()==R.id.opc5)
        {
            val preferences = getSharedPreferences("MisDatos", Context.MODE_PRIVATE)
            val editor = preferences.edit()
            editor.putBoolean("logeado", false)
            editor.apply()

            val intent = Intent(this, Sesion::class.java)
            startActivity(intent)
            finishAffinity()
        }
        if(item.getItemId()==R.id.opc6)
        {
            val cambio = Intent(this, Contacto::class.java)
            startActivity(cambio)
        }
        if(item.getItemId()==R.id.opc7)
        {
            val cambio = Intent(this, Creador::class.java)
            startActivity(cambio)
        }
        return super.onOptionsItemSelected(item)
    }
}
