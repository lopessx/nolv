<template>
  <q-page>
    <div class="row justify-center">
      <!-- Filters -->
      <div
        v-if="$q.screen.gt.xs"
        class="col-4 q-pt-xl"
      >
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
              :readonly="loading"
              @update:model-value="filterProducts(1)"
            />
          </div>
          <div class="col-6 q-pa-xs">
            <q-input
              v-model="maxPrice"
              label="Máximo"
              debounce="500"
              outlined
              :readonly="loading"
              @update:model-value="filterProducts(1)"
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
            :readonly="loading"
            @update:model-value="filterProducts(1)"
          />
        </div>
        <div class="text-subtitle1">
          Sistema operacional
        </div>
        <div class="q-pa-md">
          <q-select
            v-model="osSearch"
            label="Selecione um sistema operacional"
            :options="osOptions"
            :readonly="loading"
            @update:model-value="filterProducts(1)"
          />
        </div>
        <div class="text-subtitle1">
          Idiomas
        </div>
        <div class="q-pa-md">
          <q-select
            v-model="languageSearch"
            label="Selecione um idioma"
            :options="languageOptions"
            :readonly="loading"
            @update:model-value="filterProducts(1)"
          />
        </div>
      </div>
      <!-- Modal filters for phone -->
      <div v-else>
        <q-dialog v-model="filtersDialog">
          <div class="bg-white q-pa-md">
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
                  :disable="loading"
                  @update:model-value="filterProducts(1)"
                />
              </div>
              <div class="col-6 q-pa-xs">
                <q-input
                  v-model="maxPrice"
                  label="Máximo"
                  debounce="500"
                  outlined
                  :readonly="loading"
                  @update:model-value="filterProducts(1)"
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
                :readonly="loading"
                :options="categoryOptions"
                @update:model-value="filterProducts(1)"
              />
            </div>
            <div class="text-subtitle1">
              Sistema operacional
            </div>
            <div class="q-pa-md">
              <q-select
                v-model="osSearch"
                label="Selecione um sistema operacional"
                :options="osOptions"
                :readonly="loading"
                @update:model-value="filterProducts(1)"
              />
            </div>
            <div class="text-subtitle1">
              Idiomas
            </div>
            <div class="q-pa-md">
              <q-select
                v-model="languageSearch"
                label="Selecione um idioma"
                :options="languageOptions"
                :readonly="loading"
                @update:model-value="filterProducts(1)"
              />
            </div>
            <div class="row justify-end">
              <div class="col-6">
                <q-btn
                  v-close-popup
                  color="accent"
                  label="OK"
                />
              </div>
            </div>
          </div>
        </q-dialog>
      </div>

      <!-- Product display -->
      <div class="col-xs-11 col-sm-7">
        <q-table
          ref="tableProduct"
          color="grey-8"
          grid
          :rows="productList"
          :columns="columns"
          row-key="id"
          :filter="filter"
          :rows-per-page-options="productsPerPage"
          :loading="loading"
          no-data-label="Nenhum produto encontrado"
          hide-header
          hide-pagination
        >
          <template #top-left>
            <q-btn
              v-if="$q.screen.lt.sm"
              color="accent"
              label="filtros"
              @click="filtersDialog = true"
            />
          </template>

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
              map-options
              emit-value
              label="Ordernar"
              style="min-width: 100px;"
              @update:model-value="filterProducts(1)"
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
                      :src="imgUrl + props.cols[7].value"
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
                      <div class="text-body1 col-auto">
                        {{ props.cols[1].value }}
                      </div>
                    </div>

                    <div class="row justify-end items-end">
                      <div class="text-body1 text-weight-bold col-auto">
                        {{ props.cols[3].value }}
                      </div>
                    </div>
                  </q-card-section>
                </q-card-section>
              </q-card>
            </div>
          </template>
        </q-table>
        <div class="row justify-center q-py-md">
          <q-pagination
            v-model="page"
            color="grey-8"
            :max="pagesNumber"
            :max-pages="5"
            size="1rem"
            direction-links
            @update:model-value="changePage"
          />
        </div>
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
  { name: 'name', required: true, label: 'Nome', field: 'name', format: val => `${val}` },
  { name: 'desc', required: true, label: 'Produto', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'price', label: 'Preço (R$)', field: 'price', format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val), sortable: true },
  { name: 'language', required: true, label: 'Idioma', field: 'language', format: val => `${val}`, sortable: true },
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true },
  { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true },
  { name: 'image', required: false, label: 'Imagem', field: 'image', format: val => `${val}`, sortable: false }
]

export default defineComponent({
  name: 'Home',
  setup () {
    return {
      filtersDialog: ref(false),
      osSearch: ref(null),
      osOptions: ref([]),
      languageSearch: ref(null),
      languageOptions: ref([]),
      filter: ref(''),
      columns,
      productList: ref([]),
      ordenation: ref(),
      orderOptions: ref([{ label: 'Menor preço', value: 1 }, { label: 'Maior preço', value: 2 }]),
      minPrice: ref(null),
      maxPrice: ref(null),
      categorySearch: ref(null),
      categoryOptions: ref([]),
      productsPerPage: ref([6, 9, 15, 0]),
      imgUrl: ref(process.env.API + '/storage'),
      pagesNumber: ref(1),
      page: ref(1),
      loading: ref(false)
    }
  },
  created () {
    console.log('ex query param: ' + this.$route.query.s)
    if (this.$route.query.s) {
      this.filter = this.$route.query.s
    }
    console.log('nova página renderizada')
    this.getProducts()
    this.getCategories()
    this.getOs()
    this.getLanguages()
  },
  mounted () {
    console.log('página montada')
    window.dispatchEvent(new CustomEvent('search-products', {
      detail: {
        searchText: this.filter
      }
    }))
  },
  methods: {
    selectProduct (val) {
      console.log('produto selecionado ' + JSON.stringify(val))
      this.$router.push(`/produto/${val}`)
    },
    async getProducts () {
      this.loading = true

      api.get('/products?search=' + this.filter)
        .then((response) => {
          console.log('RESPOSTA COMPLETA: ' + JSON.stringify(response.data))
          this.pagesNumber = response.data.pagination.last_page
          console.log('length ' + response.data.pagination.data.length)
          for (let c = 0; c < response.data.pagination.data.length; c++) {
            const product = {}
            product.id = response.data.pagination.data[c].id
            product.name = response.data.pagination.data[c].name
            product.language = response.data.pagination.data[c].language_id
            product.category = response.data.pagination.data[c].category_id
            product.os = response.data.pagination.data[c].operational_system_id
            product.price = response.data.pagination.data[c].price
            product.image = response.data.pagination.data[c].main_image_path
            this.productList.push(product)
          }
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
        .finally(() => {
          this.loading = false
        })
    },
    async getCategories () {
      api.get('/categories')
        .then((response) => {
          console.log('resposta: ' + JSON.stringify(response.data))
          this.categoryOptions = response.data.categories
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    },
    async getOs () {
      api.get('/os')
        .then((response) => {
          console.log('resposta: ' + JSON.stringify(response.data))
          this.osOptions = response.data.operational_systems
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    },
    async getLanguages () {
      api.get('/languages')
        .then((response) => {
          console.log('resposta: ' + JSON.stringify(response.data))
          this.languageOptions = response.data.languages
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    },
    changePage (page) {
      this.loading = true
      let category = ''
      let os = ''
      let language = ''

      if (this.categorySearch) {
        category = this.categorySearch.value
      }

      if (this.languageSearch) {
        language = this.languageSearch.value
      }

      if (this.osSearch) {
        os = this.osSearch.value
      }

      api.get(process.env.API + '/products?page=' + page + '&category=' + category +
        '&minPrice=' + this.minPrice + '&maxPrice=' + this.maxPrice +
        '&search=' + this.filter + '&order=' + this.ordenation +
        '&os=' + os + '&language=' + language)
        .then((response) => {
          this.productList = []
          this.pagesNumber = response.data.pagination.last_page

          for (let c = 0; c < response.data.pagination.data.length; c++) {
            const product = {}
            product.id = response.data.pagination.data[c].id
            product.name = response.data.pagination.data[c].name
            product.language = response.data.pagination.data[c].language_id
            product.category = response.data.pagination.data[c].category_id
            product.os = response.data.pagination.data[c].operational_system_id
            product.price = response.data.pagination.data[c].price
            product.image = response.data.pagination.data[c].main_image_path
            this.productList.push(product)
          }
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
        .finally(() => {
          this.loading = false
        })
    },
    filterProducts (page) {
      let category = ''
      let os = ''
      let language = ''

      if (this.categorySearch) {
        category = this.categorySearch.value
      }

      if (this.languageSearch) {
        language = this.languageSearch.value
      }

      if (this.osSearch) {
        os = this.osSearch.value
      }

      api.get(process.env.API + '/products?page=' + page + '&category=' + category +
        '&minPrice=' + this.minPrice + '&maxPrice=' + this.maxPrice +
        '&search=' + this.filter + '&order=' + this.ordenation +
        '&os=' + os + '&language=' + language)
        .then((response) => {
          this.productList = []
          this.pagesNumber = response.data.pagination.last_page
          this.page = 1

          for (let c = 0; c < response.data.pagination.data.length; c++) {
            const product = {}
            product.id = response.data.pagination.data[c].id
            product.name = response.data.pagination.data[c].name
            product.language = response.data.pagination.data[c].language_id
            product.category = response.data.pagination.data[c].category_id
            product.os = response.data.pagination.data[c].operational_system_id
            product.price = response.data.pagination.data[c].price
            product.image = response.data.pagination.data[c].main_image_path
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
