<template>
  <q-page>
    <div class="row q-pa-md">
      <!-- Filters -->
      <div class="col-4 q-pt-xl">
        <div class="text-subtitle1">
          Preço
        </div>
        <div class="row q-pa-md">
          <div class="col-6 q-pa-xs">
            <q-input
              label="Mínimo"
              outlined
            />
          </div>
          <div class="col-6 q-pa-xs">
            <q-input
              label="Máximo"
              outlined
            />
          </div>
        </div>
        <div class="text-subtitle1">
          Categoria
        </div>
        <div class="q-pa-md">
          <q-select
            label="Selecione uma categoria"
          />
        </div>
        <div class="text-subtitle1">
          Sistema operacional
        </div>
        <div class="q-pa-md">
          <q-checkbox label="Linux" />
          <q-checkbox label="Windows" />
          <q-checkbox label="MacOS" />
        </div>
      </div>

      <!-- Product display -->
      <div class="col-7">
        <q-table
          color="grey-8"
          grid
          :rows="productList"
          :columns="columns"
          row-key="name"
          :filter="filter"
          :rows-per-page-options="productsPerPage"
          hide-header
        >
          <template #top-right>
            <q-input
              v-model="filter"
              type="hidden"
              borderless
              dense
              debounce="300"
              placeholder="Search"
            />
            <q-select
              v-model="ordenation"
              :options="orderOptions"
              label="Ordernar"
              style="min-width: 100px;"
            />
          </template>

          <template #item="props">
            <div class="q-pa-xs col-12 grid-style-transition">
              <q-card
                clickable
                class="cursor-pointer q-hoverable"
                @click="selectProduct(props.cols[0].value)"
              >
                <q-card-section
                  horizontal
                  class="row"
                >
                  <div class="col-5 q-pa-sm">
                    <q-img
                      src="../assets/placeholder.png"
                      spinner-color="black"
                      style="height: 150px; max-width: auto"
                    />
                  </div>
                  <q-card-section class="col-7">
                    <div class="text-body2">
                      {{ props.row.name }}
                    </div>

                    <div class="text-body1 text-weight-bold">
                      {{ props.cols[2].value }}
                    </div>
                  </q-card-section>
                </q-card-section>
              </q-card>
            </div>
          </template>
        </q-table>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
// eslint-disable-next-line no-unused-vars
import { useQuasar } from 'quasar'
import { api } from 'src/boot/axios'

const columns = [
  { name: 'id', required: true, label: 'Identificador', field: 'id', format: val => `${val}` },
  { name: 'desc', required: true, label: 'Produto', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'price', label: 'Preço (R$)', field: 'price', format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val), sortable: true },
  // { name: 'language', required: true, label: 'Idioma', field: 'language', format: val => `${val}`, sortable: true },
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true }
  // { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true }
]

const rows = [
  {
    id: 0,
    name: 'Frozen Yogurt',
    language: 'pt-BR',
    category: 'Entretenimento',
    os: 'Windows',
    price: 50.00
  },
  {
    id: 1,
    name: 'Ice cream sandwich',
    language: 'pt-BR',
    category: 'Entretenimento',
    os: 'Windows',
    price: 15.50
  },
  {
    id: 2,
    name: 'Eclair',
    language: 'pt-BR',
    category: 'Entretenimento',
    os: 'Windows',
    price: 125.15
  },
  {
    id: 3,
    name: 'Cupcake',
    language: 'pt-BR',
    category: 'Entretenimento',
    os: 'Windows',
    price: 2435.00
  },
  {
    id: 4,
    name: 'Gingerbread',
    language: 'pt-BR',
    category: 'Finança',
    os: 'Windows',
    price: 45.00
  },
  {
    id: 5,
    name: 'Jelly bean',
    language: 'pt-BR',
    category: 'Finança',
    os: 'MacOS',
    price: 55.00
  },
  {
    id: 6,
    name: 'Lollipop',
    language: 'en-US',
    category: 'Design',
    os: 'MacOS',
    price: 65.00
  },
  {
    id: 7,
    name: 'Honeycomb',
    language: 'pt-BR',
    category: 'Design',
    os: 'MacOS',
    price: 75.00
  },
  {
    id: 8,
    name: 'Donut',
    language: 'en-US',
    category: 'Produtividade',
    os: 'Linux',
    price: 85.00
  },
  {
    id: 9,
    name: 'KitKat',
    language: 'en-US',
    category: 'Produtividade',
    os: 'Linux',
    price: 95.00
  }
]

export default defineComponent({
  name: 'Home',
  setup () {
    return {
      filter: ref(''),
      columns,
      rows,
      productList: ref([]),
      ordenation: ref(),
      orderOptions: ref(['Nome', 'Preço']),
      priceRange: ref({
        min: 5,
        max: 25
      }),
      productsPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0])
    }
  },
  created () {
    console.log('nova página renderizada')
    this.getProducts()
  },
  methods: {
    selectProduct (val) {
      console.log('produto selecionado ' + JSON.stringify(val))
    },
    async getProducts () {
      api.get('/products')
        .then((response) => {
          console.log('RESPOSTA COMPLETA: ' + JSON.stringify(response.data))
          for (let c = 0; c < response.data.products.length; c++) {
            const product = {}
            product.id = response.data.products[c].id
            product.name = response.data.products[c].name
            // product.language = 'en-US'
            product.category = response.data.products[c].category_id
            product.os = response.data.products[c].operational_system_id
            product.price = response.data.products[c].price
            this.productList.push(product)
          }
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    }
  }
})
</script>
