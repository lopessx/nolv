<template>
  <q-page>
    <div class="row q-px-xl q-pt-xl q-pb-md">
      <div class="col-12">
        <div class="text-h5">
          <q-icon
            name="person"
            color="accent"
          /> Bem vindo, {{ username }}
        </div>
      </div>
    </div>
    <q-separator />
    <div class="row q-px-md q-py-md">
      <div class="col-12">
        <div class="col-12 q-pa-md q-gutter-sm">
          <q-table
            color="grey-8"
            :grid="$q.screen.gt.xs"
            :rows="productsList"
            :columns="productColumns"
            row-key="name"
            :filter="filter"
            :rows-per-page-options="productsPerPage"
            no-data-label="Nenhum produto encontrado"
            hide-header
          >
            <template #top-right>
              <q-input
                v-model="filter"
                class="q-pr-sm"
                filled
                dense
                debounce="300"
                placeholder="Pesquisar..."
              />
              <q-btn
                color="accent"
                label="Adicionar Produto"
                to="/produto/cadastro"
              />
            </template>

            <template #top-left>
              <div class="row align-center justify-center q-gutter-xs">
                <q-icon
                  name="shopping_basket"
                  color="accent"
                  size="sm"
                />
                <div class="text-subtitle1">
                  Meus produtos
                </div>
              </div>
            </template>

            <template #item="props">
              <div class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3 grid-style-transition">
                <q-card
                  clickable
                  class="cursor-pointer q-hoverable"
                  @click="selectProduct(props.cols[0].value)"
                >
                  <q-card-section>
                    <q-img
                      :src="imgUrl + props.cols[4].value"
                      spinner-color="black"
                      style="height: 150px; max-width: auto;"
                    >
                      <template #error>
                        <div class="absolute-full flex flex-center bg-white text-white">
                          <q-img
                            src="../assets/placeholder.png"
                            spinner-color="black"
                            style="height: 150px; max-width: auto;"
                          />
                        </div>
                      </template>
                    </q-img>
                  </q-card-section>
                  <q-separator />
                  <q-card-section>
                    <div class="text-body1 text-weight-bold">
                      {{ props.cols[2].value }}
                    </div>
                  </q-card-section>
                  <q-separator />
                  <div class="subtitle-1 text-weight-bold q-pa-md text-grey-7">
                    {{ props.row.name }}
                  </div>
                </q-card>
              </div>
            </template>
          </q-table>
        </div>
      </div>
      <q-separator />
      <div class="row q-px-md q-py-md">
        <div class="col-12">
          <div class="col-12 q-pa-md q-gutter-sm">
            <q-table
              color="grey-8"
              :grid="$q.screen.gt.xs"
              :rows="orders"
              :columns="orderColumns"
              row-key="name"
              :filter="filter"
              :rows-per-page-options="ordersPerPage"
              no-data-label="Nenhum pedido encontrado"
              hide-header
            >
              <template #top-right>
                <q-input
                  v-model="filter"
                  dense
                  debounce="300"
                  placeholder="Pesquisar..."
                  prefix=""
                >
                  <template #before>
                    <q-icon name="search" />
                  </template>
                </q-input>
              </template>

              <template #top-left>
                <div class="row align-center justify-center q-gutter-xs">
                  <q-icon
                    name="shopping_basket"
                    color="accent"
                    size="sm"
                  />
                  <div class="text-subtitle1">
                    Compras
                  </div>
                </div>
              </template>

              <template #item="props">
                <div class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3 grid-style-transition">
                  <q-card
                    clickable
                    class="cursor-pointer q-hoverable"
                    @click="selectProduct(props.cols[0].value)"
                  >
                    <q-card-section>
                      <q-img
                        src="../assets/placeholder.png"
                        spinner-color="black"
                        style="height: 150px; max-width: auto;"
                      />
                    </q-card-section>
                    <q-separator />
                    <q-card-section>
                      <div class="text-body1 text-weight-bold">
                        {{ props.cols[2].value }}
                      </div>
                    </q-card-section>
                    <q-separator />
                    <div class="subtitle-1 text-weight-bold q-pa-md text-grey-7">
                      {{ props.row.name }}
                    </div>
                  </q-card>
                </div>
              </template>
            </q-table>
          </div>
        </div>
        <q-separator />
        <div>
          <div />
          <div />
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'

const orderColumns = [
  { name: 'id', required: true, label: 'Identificador', field: 'id', format: val => `${val}` },
  { name: 'desc', required: true, label: 'Produto', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'total', label: 'Preço (R$)', field: 'total', format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val), sortable: true },
  { name: 'language', required: true, label: 'Idioma', field: 'language', format: val => `${val}`, sortable: true },
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true },
  { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true }
]

const productColumns = [
  { name: 'id', required: true, label: 'Identificador', field: 'id', format: val => `${val}` },
  { name: 'desc', required: true, label: 'Produto', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'price', label: 'Preço (R$)', field: 'price', format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val), sortable: true },
  // { name: 'language', required: true, label: 'Idioma', field: 'language', format: val => `${val}`, sortable: true },
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true },
  // { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true }
  { name: 'image', required: false, label: 'Imagem', field: 'image', format: val => `${val}`, sortable: false }
]

export default defineComponent({
  name: 'ClientArea',
  setup () {
    return {
      clientId: ref(''),
      email: ref(''),
      orders: ref([]),
      productsList: ref([]),
      imgUrl: ref(process.env.API + '/storage/'),
      balance: ref(0.00),
      drawer: ref(false),
      miniState: ref(true),
      username: ref(''),
      filter: ref(''),
      orderColumns,
      productColumns,
      ordersPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0]),
      productsPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0])
    }
  },

  mounted () {
    const client = this.$q.localStorage.getItem('client')

    this.clientId = client.id
    this.email = client.email
    this.username = client.name

    this.getClientOrders()
  },

  methods: {
    getClientOrders () {
      api.get(`/orders/client/${this.clientId}`)
        .then((response) => {
          const orders = response.data.orders
          if (orders && response.data.success === true) {
            orders.forEach(order => {
              this.orders.push(order)
            })
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Erro ao consultar produtos', 'negative', 'error')
        })
    },
    loginCheck () {
      this.step++
      console.log('login foi feito ' + this.otp)
    },
    deleteFromCheckout (id) {
      console.log('produto a ser deletado: ' + id)
    },
    selectProduct (val) {
      console.log('produto selecionado ' + JSON.stringify(val))
    }
  }
})
</script>

<style>

</style>
