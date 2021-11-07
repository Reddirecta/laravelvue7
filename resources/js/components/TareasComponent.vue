<template>
  <v-app>
    <div>
      <h3>Agregar Tareas</h3>
      <form @submit.prevent="agregar">
        <input
          type="text"
          name=""
          id=""
          placeholder="Nombre"
          class="form-control mb2"
          v-model="nota.nombre"
        />
        <input
          type="text"
          name=""
          id=""
          placeholder="Descripción"
          class="form-control mb2"
          v-model="nota.descripcion"
        />
        <button class="btn btn-primary" type="submit">Agregar</button>
        <v-icon large color="green darken-2"> mdi-domain </v-icon>
        <v-subheader>Subheader</v-subheader>
      </form>
      <hr class="mt-3" />
      <h3>Listado de Notas + {{numero}}</h3>
      <ul class="list-group my2">
        <li class="list-group-item" v-for="(item, index) in notas" :key="index">
          <span class="badge badge-primary float-right">
            {{ item.updated_at }}
          </span>
          <p>{{ item.nombre }}</p>
          <p>{{ item.descripcion }}</p>
        </li>
      </ul>
    </div>
      <div class="text-center d-flex align-center justify-space-around">
    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="primary"
          dark
          v-bind="attrs"
          v-on="on"
        >
          Button
        </v-btn>
      </template>
      <span>Tooltip</span>
    </v-tooltip>

    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-icon
          color="primary"
          dark
          v-bind="attrs"
          v-on="on"
        >
          mdi-home
        </v-icon>
      </template>
      <span>Tooltip</span>
    </v-tooltip>

    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <span
          v-bind="attrs"
          v-on="on"
        >This text has a tooltip</span>
      </template>
      <span>Tooltip</span>
    </v-tooltip>
  </div>
  </v-app>
</template>

<script>
import { mapState } from 'vuex'
export default {
  data() {
    return {
      notas: [],
      nota: { nombre: "", descripcion: "" },
    };
  },
  created() {
    axios.get("/notas").then((res) => {
      this.notas = res.data;
      console.log(res.data);
    });
  },
  methods: {
    agregar() {
      console.log(this.nota.nombre, this.nota.descripcion);
      const params = {
        nombre: this.nota.nombre,
        descripcion: this.nota.descripcion,
      };
      this.nota.nombre = "";
      this.nota.descripcion = "";
      axios.post("/notas", params).then((res) => {
        this.notas.push(res.data);
      });
    },
  },
  computed:{
    ...mapState(['numero'])
  }
};
</script>
