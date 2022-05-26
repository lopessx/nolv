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
    <q-expansion-item
      class="q-px-lg q-py-md"
      expand-separator
      icon="file_download"
      label="Meus produtos"
      caption="Produtos adquiridos para download"
      header-class="text-primary"
      default-opened
    >
      <div class="row">
        <div class="col-12 q-gutter-sm">
          <q-table
            color="grey-8"
            grid
            :rows="productsList"
            :columns="productColumns"
            row-key="id"
            :filter="filter"
            :rows-per-page-options="productsPerPage"
            no-data-label="Nenhum produto encontrado"
            hide-header
          >
            <template #top-right>
              <q-input
                v-model="filter"
                class="q-pr-sm"
                dense
                debounce="300"
                placeholder="Pesquisar..."
              >
                <template #before>
                  <q-icon name="search" />
                </template>
              </q-input>
            </template>

            <template #item="props">
              <div class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3 grid-style-transition">
                <q-card
                  clickable
                  class="cursor-pointer q-hoverable"
                  style="max-width: 300px;"
                  @click="selectProduct(props.cols[0].value)"
                >
                  <q-card-section>
                    <q-img
                      :src="imgUrl + props.cols[7].value"
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
                      {{ props.cols[1].value }}
                    </div>
                  </q-card-section>
                </q-card>
              </div>
            </template>
          </q-table>
        </div>
      </div>
    </q-expansion-item>

    <!--// TODO add mobile support -->
    <q-expansion-item
      class="q-px-lg q-py-md"
      expand-separator
      icon="shopping_basket"
      label="Pedidos"
      caption="Registro dos pedidos feitos"
      header-class="text-primary"
    >
      <div class="row">
        <div class="col-12 q-gutter-sm">
          <q-table
            color="grey-8"
            :rows="orders"
            :columns="orderColumns"
            row-key="id"
            :filter="filter"
            :rows-per-page-options="ordersPerPage"
            no-data-label="Nenhum pedido encontrado"
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
          </q-table>
        </div>
      </div>
    </q-expansion-item>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'

const orderColumns = [
  {
    name: 'id',
    required: true,
    label: 'Código',
    field: 'id',
    format: val => `${val}`,
    sortable: true
  },
  {
    name: 'paymethod',
    required: true,
    label: 'Método de pagamento',
    field: 'paymethod',
    format: (val) => {
      switch (val) {
        case 1:

          return 'Cartão de crédito'
        case 2:

          return 'Boleto bancário'
      }
    },
    sortable: true
  },
  {
    name: 'status',
    required: true,
    label: 'Status',
    field: 'status',
    format: (val) => {
      console.log('valor' + val)
      switch (val) {
        case 1:

          return 'Pendente'
        case 2:

          return 'Cancelado'
        case 3:

          return 'Abandonado'
        case 4:

          return 'Concluído'
      }
    },
    sortable: true
  },
  {
    name: 'total',
    label: 'Preço (R$)',
    field: 'total',
    format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val),
    sortable: true
  },
  {
    name: 'date',
    required: true,
    label: 'Data da compra',
    field: 'date',
    format: (val) => {
      const spltDate = val.split('T')
      let filterDate = spltDate[0].split('-')
      filterDate = filterDate[2] + '/' + filterDate[1] + '/' + filterDate[0]
      return filterDate
    },
    sortable: true
  }
]

const productColumns = [
  { name: 'id', required: true, label: 'Identificador', field: 'id', format: val => `${val}` },
  { name: 'name', required: true, label: 'Nome', field: 'name', format: val => `${val}` },
  { name: 'desc', required: true, label: 'Produto', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'price', label: 'Preço (R$)', field: 'price', format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val), sortable: true },
  { name: 'language', required: true, label: 'Idioma', field: 'language', format: val => `${val}`, sortable: true },
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true },
  { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true },
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
    this.getClientProducts()
  },

  methods: {
    getClientProducts () {
      api.get(`/products/client/${this.clientId}`)
        .then((response) => {
          console.log('reposta produtos ' + JSON.stringify(response.data))
          const products = response.data.products
          if (products && response.data.success === true) {
            products.forEach(product => {
              const productObj = {}
              productObj.id = product.id
              productObj.name = product.name
              productObj.language = product.language_id
              productObj.category = product.category_id
              productObj.os = product.operational_system_id
              productObj.price = product.price
              productObj.image = product.main_image_path
              this.productsList.push(productObj)
            })
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Erro ao consultar produtos', 'negative', 'error')
        })
    },
    getClientOrders () {
      api.get(`/orders/client/${this.clientId}`)
        .then((response) => {
          const orders = response.data.orders
          if (orders && response.data.success === true) {
            orders.forEach(order => {
              const orderObj = {}
              orderObj.id = order.id
              orderObj.status = order.status_id
              orderObj.paymethod = order.paymethod_id
              orderObj.total = order.total
              orderObj.date = order.created_at
              this.orders.push(orderObj)
            })
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Erro ao consultar pedidos', 'negative', 'error')
        })
    },
    selectProduct (productId) {
      console.log('produto selecionado ' + JSON.stringify(productId))
      this.$router.push(`/produto/${productId}/download`)
    },
    formatStatus (status) {
      switch (status) {
        case '1':

          return 'Pendente'
        case '2':

          return 'Cancelado'
        case '3':

          return 'Abandonado'
        case '4':

          return 'Concluído'
      }
    }
  }
})
</script>

<style>

</style>
