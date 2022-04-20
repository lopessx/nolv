<template>
  <q-page>
    <div class="row q-px-md q-pt-xl q-pb-md justify-center align-center">
      <div class="col-6 text-center">
        <q-card style="max-width: 500px;">
          <q-card-section>
            <q-img
              :src="storeImage"
              spinner-color="black"
              style="height: 250px; max-width: auto;"
            >
              <template #error>
                <div class="absolute-full flex flex-center bg-white text-white">
                  <q-img
                    src="../assets/placeholder.png"
                    spinner-color="black"
                    style="height: 250px; max-width: auto;"
                  />
                </div>
              </template>
            </q-img>
          </q-card-section>
          <q-separator />
          <q-card-section>
            <div class="text-body1 text-weight-bold q-px-md">
              <q-input
                v-model="storeName"
                outline
                readonly
                label="Nome da loja"
              />
            </div>
          </q-card-section>
          <q-separator />
          <div class="row justify-center align-center">
            <div class="col-6 subtitle-1 text-weight-bold q-pa-md text-grey-7">
              <div class="text-h5 text-accent text-weight-bold">
                {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(balance) }}
              </div>
            </div>
            <div class="col-6 q-pa-md">
              <q-btn
                color="accent"
                label="Saque"
              />
            </div>
          </div>
        </q-card>
      </div>
    </div>
    <q-separator />
    <div class="row q-px-md q-py-md">
      <div class="col-12">
        <div class="col-12 q-pa-md q-gutter-sm">
          <q-table
            color="grey-8"
            :grid="$q.screen.gt.xs"
            :rows="products"
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
                class="q-pr-sm"
                filled
                dense
                debounce="300"
                placeholder="Pesquisar..."
              />
              <q-btn
                color="accent"
                label="Adicionar Produto"
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
                  Meus pedidos
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
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'

const columns = [
  { name: 'id', required: true, label: 'Identificador', field: 'id', format: val => `${val}` },
  { name: 'desc', required: true, label: 'Produto', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'price', label: 'Preço (R$)', field: 'price', format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val), sortable: true },
  { name: 'language', required: true, label: 'Idioma', field: 'language', format: val => `${val}`, sortable: true },
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true },
  { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true }
]

export default defineComponent({
  name: 'VendorArea',
  setup () {
    return {
      hasStore: ref(true),
      storeName: ref(''),
      storeImage: ref('https://cdn.quasar.dev/img/non-existent-image-src.png'),
      email: ref(''),
      products: ref([]),
      balance: ref(0),
      drawer: ref(false),
      miniState: ref(true),
      username: ref(''),
      clientId: ref(''),
      filter: ref(''),
      columns,
      productsPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0])
    }
  },

  mounted () {
    const client = this.$q.localStorage.getItem('client')

    if (client) {
      this.email = client.email
      this.username = client.name
      this.clientId = client.id
    }
  },

  methods: {
    async getClientStore () {
      api.get(`/store/client/${this.clientId}`)
        .then((response) => {
          if (response.data.success === true) {
            const products = response.data.products

            this.storeName = response.data.store.name
            this.storeImage = response.data.store.img_path
            this.balance = response.data.store.balance

            if (products && products.length > 0) {
              products.forEach(product => {
                this.products.push({ id: product.id, price: product.balance, name: product.name, img_path: product.img_path })
              })
            }
          } else {
            // TODO maybe add register vendor page
            this.hasStore = false
          }
        })
        .catch((error) => {
          console.error('error message ' + error.message + ' code ' + error.code)
        })
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
