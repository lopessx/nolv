<template>
  <q-page>
    <q-separator class="bg-accent" />
    <div class="row q-pa-md">
      <div class="col-12 q-px-md text-h4">
        Novidades
      </div>
      <div
        :key="product.id"
        v-for="product in productList"
        class="col-sm-12 col-md-4 col-lg-3 q-pa-md"
      >
        <q-card
          bordered
          class="my-card cursor-pointer q-hoverable"
          clickable
          @click="selectProduct(product.id)"
        >
          <q-card-section>
            <q-img
              :src="imgUrl + product.image"
              spinner-color="black"
              style="height: 150px; max-width: auto"
            >
              <template #error>
                <q-img src="../assets/placeholder.png" />
              </template>
            </q-img>
          </q-card-section>

          <q-card-section>
            <div class="text-h6">
              {{ product.name }}
            </div>
            <div class="text-subtitle1 text-weight-bold text-accent">
              {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(product.price) }}
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
    <q-separator color="accent" />
    <div class="row q-pa-md align-center justify-center text-center">
      <div class="col-sm-11 col-md-6">
        <div class="q-py-md text-primary">
          <q-icon
            name="verified_user"
            size="xl"
          />
        </div>
        <div class="q-pa-md text-h6">
          Seu Marketplace Confiável para Produtos Digitais
        </div>
        <div>
          Descubra o marketplace ideal para produtos digitais: segurança garantida, suporte especializado e atualizações contínuas para você aproveitar o melhor de cada compra.
        </div>
      </div>
      <div class="col-sm-11 col-md-4">
        <q-card
          dark
          bordered
          class="bg-primary my-card"
        >
          <img
            src="../assets/home-cta.webp"
            height="300"
          >
          <q-card-section>
            <div class="text-h6">
              Venda sua solução no nosso site!
            </div>
            <div class="text-subtitle2">
              Venda produtos digitais de forma fácil e rápida agora.
            </div>
          </q-card-section>

          <q-card-actions align="center">
            <q-btn
              color="accent"
              label="Vender"
              @click="$router.push('/produto/cadastro')"
            />
          </q-card-actions>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
// eslint-disable-next-line no-unused-vars
import { useQuasar } from 'quasar'
import { api } from 'src/boot/axios'

export default defineComponent({
  name: 'Home',
  setup () {
    return {
      slide: ref(1),
      autoplay: ref(true),
      lorem: 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Itaque voluptatem totam, architecto cupiditate officia rerum, error dignissimos praesentium libero ab nemo.',
      productList: ref([]),
      imgUrl: ref(process.env.API + '/storage')
    }
  },
  created () {
    this.getProducts()
    this.getCategories()
    this.getOs()
    this.getLanguages()
  },
  mounted () {
  },
  methods: {
    selectProduct (val) {
      this.$router.push(`/produto/${val}`)
    },
    async getProducts () {
      this.loading = true

      api.get('/products')
        .then((response) => {
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
          this.categoryOptions = response.data.categories
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    },
    async getOs () {
      api.get('/os')
        .then((response) => {
          this.osOptions = response.data.operational_systems
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    },
    async getLanguages () {
      api.get('/languages')
        .then((response) => {
          this.languageOptions = response.data.languages
        })
        .catch((error) => {
          console.error('erro identificado ' + error.message + ' code ' + error.code)
        })
    }
  }
})
</script>
