<template>
  <q-page>
    <div class="row align-center justify-start q-gutter-xs q-px-md q-pt-lg q-pb-md">
      <q-icon
        name="person"
        color="accent"
        size="sm"
      />
      <div class="text-subtitle1">
        Perfil
      </div>
    </div>
    <q-separator />
    <div class="row q-px-md q-py-md">
      <div class="col-6">
        <div class="row justify-start q-pa-md">
          <q-icon
            name="person"
            color="accent"
            size="sm"
          />
          <div class="text-subtitle1">
            Dados pessoais
          </div>
        </div>
        <q-input
          v-model="name"
          outlined
          label="Nome completo"
          class="q-py-md q-px-sm"
        />
        <q-input
          v-model="email"
          outlined
          label="E-mail"
          type="email"
          class="q-py-md q-px-sm"
        />
        <q-input
          v-model="phone"
          outlined
          label="Número de telefone"
          type="phone"
          mask="(##) #####-####"
          class="q-py-md q-px-sm"
        />
      </div>
      <q-separator vertical />
      <div class="col-5">
        <div class="row justify-start q-pa-md">
          <q-icon
            name="credit_card"
            color="accent"
            size="sm"
          />
          <div class="text-subtitle1">
            Cartões cadastrados
          </div>
        </div>
        <q-list bordered>
          <div
            v-for="card in cards"
            :key="card.id"
          >
            <q-item
              v-ripple
              clickable
              class="q-pa-lg"
            >
              <q-item-section>{{ card.number }} - {{ card.brand }}</q-item-section>
              <q-item-section avatar>
                <q-icon
                  color="accent"
                  name="delete"
                  @click="deleteCard(card.id)"
                />
              </q-item-section>
            </q-item>
            <q-separator inset />
          </div>
        </q-list>
      </div>
      <div class="row col-12 justify-center q-pa-xl">
        <q-btn
          color="accent"
          label="Confirmar"
          @click="saveProfile()"
        />
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
      products: ref([{ id: 0, name: 'Abacaxi', price: 21.00 }, { id: 1, name: 'Banana', price: 10.00 }, { id: 2, name: 'Batata', price: 34.23 }]),
      balance: ref(19.23),
      drawer: ref(false),
      miniState: ref(true),
      username: ref('João'),
      filter: ref(''),
      columns,
      rows,
      productsPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0]),
      name: ref(''),
      phone: ref(''),
      cards: ref([{ id: 0, number: 'xxxxxxxx4754', brand: 'Visa' }, { id: 1, number: 'xxxxxxxx5055', brand: 'Master' }]),
      lorem: ref('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')
    }
  },

  mounted () {
    console.log('carregado')
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
    },
    deleteCard (val) {
      console.log('cartão deletado ' + val)
    },
    saveProfile () {
      console.log('alterações salvas')
    }
  }
})
</script>

<style>

</style>
