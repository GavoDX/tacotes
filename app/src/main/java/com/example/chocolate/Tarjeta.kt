package com.example.chocolate

import android.Manifest
import android.content.Intent
import android.content.pm.PackageManager
import android.net.Uri
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

class Tarjeta : AppCompatActivity() {

    private lateinit var Nombre: TextView
    private lateinit var Marca: TextView
    private lateinit var Pais: TextView
    private lateinit var Presentacion: TextView
    private lateinit var PCACAO: TextView
    private lateinit var Cacao: TextView
    private lateinit var Sabor: TextView
    private lateinit var Tipo: TextView
    private lateinit var Peso: TextView
    private lateinit var Tel: TextView
    private lateinit var tTel: Button
    private lateinit var volver: Button
    private var numeroTelefono: String = ""
    private val requestCall = 1

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_tarjeta)

        Nombre = findViewById(R.id.Nombre)
        Marca = findViewById(R.id.Marca)
        Pais = findViewById(R.id.Pais)
        Presentacion = findViewById(R.id.Presentacion)
        PCACAO = findViewById(R.id.PCACAO)
        Cacao = findViewById(R.id.Cacao)
        Sabor = findViewById(R.id.Sabor)
        Tipo = findViewById(R.id.Tipo)
        Peso = findViewById(R.id.Peso)
        Tel = findViewById(R.id.Tel)
        tTel = findViewById(R.id.tTel)
        volver = findViewById(R.id.volver)

        val position = intent.getIntExtra("pos", -1)

        if (position != -1) {
            val item = ListaChocolate.listaChocolates[position]
            Nombre.text = "Nombre: ${item.nombre}"
            Marca.text = "Marca: ${item.marca}"
            Pais.text = "País: ${item.pais}"
            Presentacion.text = "Presentación: ${item.presentacion}"
            PCACAO.text = "% Cacao: ${item.PCACAO}"
            Cacao.text = "Tipo de Cacao: ${item.cacao}"
            Sabor.text = "Sabor: ${item.sabor}"
            Tipo.text = "Tipo de chocolate: ${item.tipo}"
            Peso.text = "Peso: ${item.peso}"
            Tel.text = "Telefono: ${item.tel}"
            numeroTelefono = item.tel
        }

        tTel.setOnClickListener {
            llamar()
        }
        volver.setOnClickListener {
            val cambio= Intent(this, Ver::class.java)
            startActivity(cambio)
        }

        val mainView = findViewById<android.view.View>(R.id.main)
        if (mainView != null) {
            ViewCompat.setOnApplyWindowInsetsListener(mainView) { v, insets ->
                val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
                v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
                insets
            }
        }
    }

    private fun llamar() {
        if (numeroTelefono.trim().isNotEmpty()) {
            if (ContextCompat.checkSelfPermission(this, Manifest.permission.CALL_PHONE) != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(this, arrayOf(Manifest.permission.CALL_PHONE), requestCall)
            } else {
                val dialIntent = Intent(Intent.ACTION_CALL)
                dialIntent.data = Uri.parse("tel:$numeroTelefono")
                startActivity(dialIntent)
            }
        } else {
            Toast.makeText(this, "Número no disponible", Toast.LENGTH_SHORT).show()
        }
    }

    override fun onRequestPermissionsResult(requestCode: Int, permissions: Array<out String>, grantResults: IntArray) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults)
        if (requestCode == requestCall) {
            if (grantResults.isNotEmpty() && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                llamar()
            } else {
                Toast.makeText(this, "Permiso denegado", Toast.LENGTH_SHORT).show()
            }
        }
    }
}