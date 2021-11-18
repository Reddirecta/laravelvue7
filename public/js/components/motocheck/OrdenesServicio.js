Vue.component ('ordenesservicio',{
	template://html
	`
    <div data-app>
    <v-app>
        <v-card class="d-inline-block ma-1 pa-1" >
            <v-card-text class="pa-1">
                <template>
                <v-row>
                    <v-col xs="12" md="6">
                        <v-text-field v-model="buscar"
                        append-icon="mdi-magnify"
                        label="Buscar"
                        single-line
                        hide-details
                        clearable
                        solo
                        dense
                        :autofocus=false
                        ></v-text-field>
                    </v-col>
                </v-row>
                    <v-row>
                        <v-col xs="12" md="12">
                            <v-data-table
                            :headers="headers"
                            :items="registros"
                            :page.sync="page"
                            :options.sync="options"
                            :server-items-length="totalRegistros"
                            :loading="loading"
                            :items-per-page="itemsPerPage"
                            hide-default-footer
                            class="elevation-1"
                            @page-count="pageCount = $event"
                            dense
                            >
                             <template v-slot:item.actions="{ item }">
                              <v-icon
                                small
                                class="mr-2"
                                @click="editItem(item)"
                              >
                                mdi-pencil
                              </v-icon>
                              <v-icon
                                small
                                @click="deleteItem(item)"
                              >
                                mdi-delete
                              </v-icon>
                            </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                    <v-row class="mt-2" align="center" justify="center">
                        <v-spacer></v-spacer>
                        <span class="grey--text">Registros por página</span>
                        <v-menu offset-y>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    dark
                                    text
                                    color="blue"
                                    class="ml-2"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    {{ itemsPerPage }}
                                    <v-icon>mdi-chevron-down</v-icon>
                                </v-btn>
                            </template>
                        <v-list>
                          <v-list-item
                            v-for="(number, index) in itemsPerPageArray"
                            :key="index"
                            @click="updateItemsPerPage(number)"
                          >
                            <v-list-item-title>{{ number }}</v-list-item-title>
                          </v-list-item>
                        </v-list>
                      </v-menu>
                      {{totalRegistros}} registros
                      <v-spacer></v-spacer>
                      <div class="text-center pt-2">
                        <v-pagination v-model="page" :length="pageCount" total-visible="8"></v-pagination>
                      </div>
                      <v-spacer></v-spacer>
                    </v-row>
                </template>
            </v-card-text>
        </v-card>
        <v-dialog
        v-model="dialog"
        max-width="500px"
      >
        <v-card>
          <v-card-title>
            <span class="text-h5">Editar Servicio</span>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-text-field
                    v-model="editedItem.modelo"
                    label="Unidad"
                  ></v-text-field>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                <v-select
                v-model="editedItem.corto"
                :items="mecanicos"
                label="Mecánico"
                item-text="corto"
                @change="actualizaMid()"       
                ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col
                  cols="12"
                  sm="12"
                  md="12"
                >
                  <v-textarea
                    v-model="editedItem.trabajo"
                    label="Trabajo Realizado"
                  ></v-textarea>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="blue darken-1"
              text
              @click="close"
            >
              Cancelar
            </v-btn>
            <v-btn
              color="blue darken-1"
              text
              @click="save"
            >
              Guardar Cambios
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialogDelete" max-width="500px">
        <v-card>
          <v-card-title class="text-h5">¿Seguro que deseas eliminar el registro?</v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="closeDelete">Cancelar</v-btn>
            <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-app>
    </div>
`,
    data(){
		return{
            mecanicos: [],
            dialog: false,
            dialogDelete: false,
            buscar:null,
            itemsPerPageArray: [5, 10, 15],
            page: 1,
            pageCount: 0,
            itemstemp: null,
            totaltemp: null,
            itemsPerPage: 10,
            totalRegistros: 0,
            registros: [],
            loading: true,
            options: {},
            mensaje: null,
            elmensaje: false,
            headers: [
              {
                text: 'Folio',
                align: 'start',
                sortable: false,
                value: 'folio',
              },
              { text: 'Fecha', value: 'fecha', sortable: false, },
              { text: 'Unidad', value: 'modelo', sortable: false, },
              { text: 'Serie', value: 'serie', sortable: false, },
              { text: 'Mecánico', value: 'corto', sortable: false, },
              { text: 'Acciones', value: 'actions', sortable: false },
            ],
            editedIndex: -1,
            editedItem: {
              unidad: '',
              mecanico: '',
              trabajo: '',
            },
        }
    },
    methods:{
      //Se requiere para mandar el id del mecanico
      actualizaMid(){
        const elemento = this.mecanicos.find(element => element.corto == this.editedItem.corto)
        this.editedItem.mid = elemento.id
        console.log(elemento)
        return
      },
      async editItem (item) {
        this.editedIndex = this.registros.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },
      deleteItem (item) {
        this.editedIndex = this.registros.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true

      },
      deleteItemConfirm () {
        this.registros.splice(this.editedIndex, 1)
        this.closeDelete()
      },
      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
        //Actualizar datos, tambien sirve para guardar, aunque no esta implementado aquí
      async save () {
        if (this.editedIndex > -1) {
          Object.assign(this.registros[this.editedIndex], this.editedItem)
          const params = {modelo: this.editedItem.modelo, corto: this.editedItem.corto, trabajo: this.editedItem.trabajo, mid: this.editedItem.mid}
          await axios.put(`/os/${this.editedItem.id}`,params)
          .then(res => {
            console.log(res)
          })
        } else {
          this.registros.push(this.editedItem)
        }
        this.close()
      },
        //Obtener Ordenes de Servicio
        async getOs () {
            this.loading = true
              const { sortBy, sortDesc, page, itemsPerPage } = this.options
              await axios.get(`/getdatos?ordenpor=${sortBy}&ordentipo=${sortDesc}&pagina=${page}&registrosporpagina=${itemsPerPage}&buscar=${this.buscar}`)
              .then(({ data }) => {
                this.itemstemp = data.datos
                this.totaltemp = data.totales 
              })
              .catch((error) => (console.log(error)))
              .then(() => (this.loading = false))
              let items = this.itemstemp
              const total = this.totaltemp
              let salida = []
              salida['items'] = items
              salida['total'] = parseInt(total,10)
              return salida
          },
          updateItemsPerPage (number) {
            this.itemsPerPage = number
          },
          getMensaje (id) {
            console.log(id)
            const resultado = this.registros.find( registro => registro.id === id );
            this.mensaje = resultado.mensaje
            this.elmensaje = true
          },
          async getMecanicos(){
            await axios.get('/getmecanicos')
            .then(({data})=>{
              this.mecanicos = data
              console.log(data)
            })
            .catch((error) => (console.log(error)))
            .then(() => (console.log('mecanicos ok')))
          }
    },
    mounted(){
        this.getMecanicos()
        this.getOs()
        .then(data => {
          this.registros = data.items
          this.totalRegistros = parseInt(data.total,10)
        })
    },
    watch:{
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
        options: {
            handler () {
              this.getOs()
                .then(data => {
                  this.registros = data.items
                  this.totalRegistros = parseInt(data.total,10)
                })
            },
            deep: true,
          },
        itemsPerPage (val) {
            this.options.itemsPerPage = val
          },
        buscar() {
            this.getOs()
            .then(data => {
                console.log(data)
                this.registros = data.items
                this.totalRegistros = parseInt(data.total,10)
              })
        }
    }
});

new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    data:{}
})
