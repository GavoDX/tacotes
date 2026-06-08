package com.example.chocolate

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.CheckBox
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView

class Eliminaadapter(private val lista: List<Datos>) : RecyclerView.Adapter<Eliminaadapter.ViewHolder>() {

    val itemsSeleccionados = mutableListOf<Datos>()
    class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val nombre: TextView = view.findViewById(R.id.Nombre)
        val tipo: TextView = view.findViewById(R.id.Tipo)
        val peso: TextView = view.findViewById(R.id.Peso)
        val check: CheckBox = view.findViewById(R.id.check)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.eliminar_holder, parent, false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val item = lista[position]
        holder.nombre.text = "Nombre: ${item.nombre}"
        holder.tipo.text = "Tipo: ${item.tipo}"
        holder.peso.text = "Peso: ${item.peso}"
        holder.check.setOnCheckedChangeListener(null)
        holder.check.isChecked = itemsSeleccionados.contains(item)
        holder.check.setOnCheckedChangeListener { _, isChecked ->
            if (isChecked) {
                if (!itemsSeleccionados.contains(item)) {
                    itemsSeleccionados.add(item)
                }
            } else {
                itemsSeleccionados.remove(item)
            }
        }
    }

    override fun getItemCount(): Int = lista.size
}