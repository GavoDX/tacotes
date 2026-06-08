package com.example.chocolate

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView

class ChocoAdapter(private val lista: List<Datos>) :
    RecyclerView.Adapter<ChocoAdapter.ViewHolder>() {

    class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val nombre: TextView = view.findViewById(R.id.Nombre)
        val marca: TextView = view.findViewById(R.id.Marca)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_holder, parent, false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val item = lista[position]
        holder.nombre.text = item.nombre
        holder.marca.text = item.marca
        holder.itemView.setOnClickListener {
            val context = holder.itemView.context
            val intent = android.content.Intent(context, Tarjeta::class.java)
            intent.putExtra("pos", position)
            context.startActivity(intent)
        }
    }

    override fun getItemCount(): Int = lista.size
}
