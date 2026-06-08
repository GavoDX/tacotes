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
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

class Actualizar : AppCompatActivity() {

    private lateinit var info1n: EditText
    private lateinit var info2m: EditText
    private lateinit var info3p: EditText
    private lateinit var info4t: EditText
    private lateinit var presentacion: Spinner
    private lateinit var pcacao: EditText
    private lateinit var cacao: Spinner
    private lateinit var sabor: Spinner
    private lateinit var tipo: Spinner
    private lateinit var peso: Spinner
    private var indice: Int = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_actualizar)

        if (ListaChocolate.listaChocolates.isEmpty()) {
            Toast.makeText(this, "No se han creado chocolates", Toast.LENGTH_SHORT).show()
        }

        val toolbar = findViewById<androidx.appcompat.widget.Toolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)

        info1n = findViewById(R.id.info1n)
        info2m = findViewById(R.id.info2m)
        info3p = findViewById(R.id.info3p)
        info4t = findViewById(R.id.info4t)
        presentacion = findViewById(R.id.Presentacion)
        pcacao = findViewById(R.id.info5t)
        cacao = findViewById(R.id.Cacao)
        sabor = findViewById(R.id.Sabor)
        tipo = findViewById(R.id.Tipo)
        peso = findViewById(R.id.Peso)

        configurarSpinners()

        findViewById<Button>(R.id.Anterior).setOnClickListener {
            if (ListaChocolate.listaChocolates.isNotEmpty()) {
                val num = ListaChocolate.listaChocolates.size
                indice = if (indice > 0) indice - 1 else num - 1
                mostrarDatos()
            }
        }

        findViewById<Button>(R.id.Siguiente).setOnClickListener {
            if (ListaChocolate.listaChocolates.isNotEmpty()) {
                val num = ListaChocolate.listaChocolates.size
                indice = if (indice < num - 1) indice + 1 else 0
                mostrarDatos()
            }
        }

        findViewById<Button>(R.id.Guardar).setOnClickListener {
            actualizarDatos()
        }

        if (ListaChocolate.listaChocolates.isNotEmpty()) {
            indice = 0
            mostrarDatos()
        }

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }
    }

    private fun mostrarDatos() {
        val pos = indice
        if (pos < ListaChocolate.listaChocolates.size) {
            val chocolate = ListaChocolate.listaChocolates[pos]
            info1n.setText(chocolate.nombre)
            info2m.setText(chocolate.marca)
            info3p.setText(chocolate.pais)
            info4t.setText(chocolate.tel)
            setSpinnerSelection(presentacion, chocolate.presentacion, R.array.presentacion)
            pcacao.setText(chocolate.PCACAO)
            setSpinnerSelection(cacao, chocolate.cacao, R.array.tipocacao)
            setSpinnerSelection(sabor, chocolate.sabor, R.array.perfildesabor)
            setSpinnerSelection(tipo, chocolate.tipo, R.array.tipo)
            setSpinnerSelection(peso, chocolate.peso, R.array.peso)

            Toast.makeText(this, "Chocolate #${pos + 1}", Toast.LENGTH_SHORT).show()
        }
    }

    private fun actualizarDatos() {
        if (ListaChocolate.listaChocolates.isEmpty()) {
            Toast.makeText(this, "No hay chocolates para actualizar", Toast.LENGTH_SHORT).show()
            return
        }
        val pos = indice
        if (pos < ListaChocolate.listaChocolates.size) {
            val chocolateActualizado = Datos(
                nombre = info1n.text.toString(),
                marca = info2m.text.toString(),
                pais = info3p.text.toString(),
                tel = info4t.text.toString(),
                presentacion = presentacion.selectedItem.toString(),
                PCACAO = pcacao.text.toString(),
                cacao = cacao.selectedItem.toString(),
                sabor = sabor.selectedItem.toString(),
                tipo = tipo.selectedItem.toString(),
                peso = peso.selectedItem.toString()
            )
            ListaChocolate.listaChocolates[pos] = chocolateActualizado
            Toast.makeText(this, "Chocolate actualizado", Toast.LENGTH_SHORT).show()
        }
    }

    private fun configurarSpinners() {
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
    }

    private fun setSpinnerSelection(spinner: Spinner, value: String, arrayResId: Int) {
        val array = resources.getStringArray(arrayResId)
        val index = array.indexOf(value)
        if (index >= 0) spinner.setSelection(index)
    }

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menu, menu)
        return true
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
            Toast.makeText(this, "Ya estás en la opción", Toast.LENGTH_LONG).show()
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
