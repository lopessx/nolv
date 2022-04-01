<template>
  <q-page>
    <!-- // TODO transformar drawer em componente -->
    <q-drawer
      v-model="drawer"
      show-if-above

      :mini="miniState"
      mini-to-overlay
      :width="200"
      :breakpoint="500"

      bordered
      class="bg-primary text-secondary"
      @mouseover="miniState = false"
      @mouseout="miniState = true"
    >
      <q-scroll-area class="fit">
        <q-list padding>
          <q-item
            v-ripple
            clickable
          >
            <q-item-section avatar>
              <q-icon
                name="home"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Início
            </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            v-ripple
            clickable
          >
            <q-item-section avatar>
              <q-icon
                name="person"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Perfil
            </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            v-ripple
            clickable
          >
            <q-item-section avatar>
              <q-icon
                name="store"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Minha loja
            </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            v-ripple
            clickable
          >
            <q-item-section avatar>
              <q-icon
                name="contact_support"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Suporte
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>
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
            :rows="products"
            :columns="columns"
            row-key="name"
            :filter="filter"
            :rows-per-page-options="productsPerPage"
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
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'

const columns = [
  { name: 'id', required: true, label: 'Identificador', field: 'id', format: val => `${val}` },
  { name: 'desc', required: true, label: 'Produto', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'price', label: 'Preço (R$)', field: 'price', format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val), sortable: true },
  { name: 'language', required: true, label: 'Idioma', field: 'language', format: val => `${val}`, sortable: true },
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true },
  { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true }
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
  name: 'ClientArea',
  setup () {
    return {
      email: ref(''),
      products: ref([]),
      balance: ref(0.00),
      drawer: ref(false),
      miniState: ref(true),
      username: ref(''),
      filter: ref(''),
      columns,
      rows,
      productsPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0])
    }
  },

  mounted () {
    const client = this.$q.sessionStorage.getItem('client')

    this.email = client.email
    this.username = client.name
  },

  methods: {
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
