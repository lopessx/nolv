<template>
  <q-page>
    <div class="row justify-center">
      <!-- Filters -->
      <div class="col-4 q-pt-xl">
        <div class="text-subtitle1">
          Preço
        </div>
        <div class="row q-py-md justify-center">
          <div class="col-6 q-pa-xs">
            <q-input
              v-model="minPrice"
              label="Mínimo"
              debounce="500"
              outlined
              @update:model-value="filterPriceMin()"
            />
          </div>
          <div class="col-6 q-pa-xs">
            <q-input
              v-model="maxPrice"
              label="Máximo"
              debounce="500"
              outlined
              @update:model-value="filterPriceMax()"
            />
          </div>
        </div>
        <div class="text-subtitle1">
          Categoria
        </div>
        <div class="q-pa-md">
          <q-select
            v-model="categorySearch"
            label="Selecione uma categoria"
            :options="categoryOptions"
            @update:model-value="filterCategory()"
          />
        </div>
        <!--
        <div class="text-subtitle1">
          Sistema operacional
        </div>
        <div class="q-pa-md">
          <q-checkbox label="Linux" />
          <q-checkbox label="Windows" />
          <q-checkbox label="MacOS" />
        </div>
        -->
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
          no-data-label="Nenhum produto encontrado"
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
                      :src="imgUrl + props.cols[4].value"
                      spinner-color="black"
                      style="height: 150px; max-width: auto"
                    >
                      <template #error>
                        <q-img src="../assets/placeholder.png" />
                      </template>
                    </q-img>
                  </div>
                  <q-card-section class="col-7">
                    <div class="row justify-start items-start">
                      <div class="text-body2 col-auto">
                        {{ props.row.name }}
                      </div>
                    </div>

                    <div class="row justify-end items-end">
                      <div class="text-body1 text-weight-bold col-auto">
                        {{ props.cols[2].value }}
                      </div>
                    </div>

                    <!-- // TODO create OS && language indicator?
                    <div class="row justify-start items-end">
                      <div class="text-body1 text-weight-bold col-auto">
                        {{ props.cols[3].value }}
                      </div>
                    </div>
-->
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
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true },
  // { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true }
  { name: 'image', required: false, label: 'Imagem', field: 'image', format: val => `${val}`, sortable: false }
]

export default defineComponent({
  name: 'Home',
  setup () {
    return {
      filter: ref(''),
      columns,
      productList: ref([]),
      ordenation: ref(),
      orderOptions: ref(['Nome', 'Preço']),
      minPrice: ref(null),
      maxPrice: ref(null),
      categorySearch: ref(null),
      categoryOptions: ref([]),
      productsPerPage: ref([6, 9, 15, 0]),
      imgUrl: ref(process.env.API + '/storage')
    }
  },
  created () {
    console.log('nova página renderizada')
    this.getProducts()
    this.getCategories()
  },
  methods: {
    selectProduct (val) {
      console.log('produto selecionado ' + JSON.stringify(val))
      this.$router.push(`/produto/${val}`)
    },
    async getProducts () {
      api.get('/products')
        .then((response) => {
          console.log('RESPOSTA COMPLETA: ' + JSON.stringify(response.data))
          for (let c = 0; c < response.data.products.length; c++) {
            const product = {}
            product.id = response.data.products[c].id
            product.name = response.data.products[c].name
            product.language = response.data.products[c].language_id
            product.category = response.data.products[c].category_id
            product.os = response.data.products[c].operational_system_id
            product.price = response.data.products[c].price
            product.image = response.data.products[c].main_image_path
            this.productList.push(product)
          }
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    },
    async getCategories () {
      api.get('/categories')
        .then((response) => {
          console.log('resposta: ' + JSON.stringify(response.data))
          this.categoryOptions = response.data.category
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    },
    filterCategory () {
      // TODO implement filter
      console.log('categoria procurar: ' + this.categorySearch.label)
    },
    filterPriceMin () {
      console.log('preço min a ser filtrado ' + this.minPrice)
    },
    filterPriceMax () {
      console.log('preço max a ser filtrado ' + this.maxPrice)
    }
  }
})
</script>
