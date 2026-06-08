package com.example.chocolate

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.Menu
import android.view.MenuItem
import android.widget.ArrayAdapter
import android.widget.Button
import android.widget.EditText
import android.widget.Spinner
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.Toolbar

class MainActivity : AppCompatActivity() {

    private lateinit var nombre: EditText
    private lateinit var marca: EditText
    private lateinit var pais: EditText
    private lateinit var tel: EditText
    private lateinit var presentacion: Spinner
    private lateinit var PCACAO: EditText
    private lateinit var cacao: Spinner
    private lateinit var sabor: Spinner
    private lateinit var tipo: Spinner
    private lateinit var peso: Spinner

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        nombre = findViewById(R.id.Nombre)
        marca = findViewById(R.id.Marca)
        pais = findViewById(R.id.Pais)
        tel = findViewById(R.id.Tel)
        presentacion = findViewById(R.id.Presentacion)
        PCACAO = findViewById(R.id.PCACAO)
        cacao = findViewById(R.id.Cacao)
        sabor = findViewById(R.id.Sabor)
        tipo = findViewById(R.id.Tipo)
        peso = findViewById(R.id.Peso)

        val adapterPres = ArrayAdapter.createFromResource(this, R.array.presentacion, android.R.layout.simple_spinner_item)
        adapterPres.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
        presentacion.adapter = adapterPres

        val adapterTipoCacao = ArrayAdapter.createFromResource(this, R.array.tipocacao, android.R.layout.simple_spinner_item)
        adapterTipoCacao.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
        cacao.adapter = adapterTipoCacao

        val adapterPerfilSabor = ArrayAdapter.createFromResource(this, R.array.perfildesabor, android.R.layout.simple_spinner_item)
        adapterPerfilSabor.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
        sabor.adapter = adapterPerfilSabor

        val adapterTipo = ArrayAdapter.createFromResource(this, R.array.tipo, android.R.layout.simple_spinner_item)
        adapterTipo.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
        tipo.adapter = adapterTipo

        val adapterPeso = ArrayAdapter.createFromResource(this, R.array.peso, android.R.layout.simple_spinner_item)
        adapterPeso.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
        peso.adapter = adapterPeso


        val toolbar = findViewById<androidx.appcompat.widget.Toolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)

        findViewById<Button>(R.id.mostrar).setOnClickListener { mostrar() }
        findViewById<Button>(R.id.guardar).setOnClickListener { guardar() }
    }

    private fun mostrar() {
        val cambio = Intent(this, Ver::class.java)
        startActivity(cambio)
    }

    private fun guardar() {
        if (nombre.text.isEmpty() || marca.text.isEmpty() || pais.text.isEmpty() || tel.text.isEmpty() || PCACAO.text.isEmpty()) {
            Toast.makeText(this, "Por favor llena todos los datos", Toast.LENGTH_SHORT).show()
        } else {
            val info = Datos(
                nombre = nombre.text.toString(),
                marca = marca.text.toString(),
                pais = pais.text.toString(),
                tel = tel.text.toString(),
                presentacion = presentacion.selectedItem.toString(),
                PCACAO = PCACAO.text.toString(),
                cacao = cacao.selectedItem.toString(),
                sabor = sabor.selectedItem.toString(),
                tipo = tipo.selectedItem.toString(),
                peso = peso.selectedItem.toString()
            )
            ListaChocolate.listaChocolates.add(info)
            Toast.makeText(this, "Ya guardó la info", Toast.LENGTH_SHORT).show()
            limpiarCampos()
        }
    }

    private fun limpiarCampos() {
        nombre.text.clear()
        marca.text.clear()
        pais.text.clear()
        tel.text.clear()
        presentacion.setSelection(0)
        PCACAO.text.clear()
        cacao.setSelection(0)
        sabor.setSelection(0)
        tipo.setSelection(0)
        peso.setSelection(0)
    }

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menu, menu)
        return super.onCreateOptionsMenu(menu)
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        if (item.getItemId()==R.id.opc1)
        {
            Toast.makeText(this, "Ya estás en la opción", Toast.LENGTH_LONG).show()
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
            val cambio = Intent(this, Eliminar::class.java)
            startActivity(cambio)
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
