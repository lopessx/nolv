<template>
  <q-page>
    <div class="row q-px-lg q-pt-lg q-pb-md q-gutter-xs justify-center">
      <div class="col-xs-12 col-sm-8">
        <q-carousel
          v-if="imgs.length > 0"
          v-model="slide"
          control-type="push"
          control-color="accent"
          animated
          navigation
          infinite
          arrows
          transition-prev="slide-right"
          transition-next="slide-left"
        >
          <q-carousel-slide
            v-for="img in imgs"
            :key="img.order"
            :name="img.order"
            :img-src="imgUrl + img.path"
          />
        </q-carousel>
        <q-carousel
          v-else
          v-model="slide"
          control-type="push"
          control-color="accent"
          animated
          navigation
          infinite
          arrows
          transition-prev="slide-right"
          transition-next="slide-left"
        >
          <q-carousel-slide
            :key="0"
            :name="1"
          >
            <q-img
              src="../assets/placeholder.png"
              spinner-color="black"
              style="height: 325px; max-width: auto;"
            />
          </q-carousel-slide>
        </q-carousel>
      </div>
    </div>

    <q-separator color="grey" />

    <div class="row q-px-lg q-py-md justify-center">
      <div class="row col-xs-12 col-sm-6 q-pb-md justify-start">
        <div class="col-8 ">
          <div class="text-h5">
            {{ productName }}
          </div>
          <div class="text-subtitle1 text-grey text-weight-bold q-pb-sm">
            v {{ version }}
          </div>
        </div>
        <div class="col-8">
          <q-rating
            v-model="ratingAverage"
            readonly
            size="2em"
            :max="5"
            color="accent"
          />
          {{ ratingQtd }}
        </div>
      </div>
      <div class="row col-xs-12 col-sm-6 q-pb-md justify-end">
        <div class="row col-xs-12 col-sm-8 text-h6 text-accent text-weight-bold">
          {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(price) }}
        </div>
        <div class="row col-xs-12 col-sm-8 q-py-md">
          <q-btn
            class="col-xs-12 col-sm-8"
            :loading="loading"
            color="accent"
            label="Comprar"
            @click="addToCart"
          />
        </div>
      </div>
    </div>

    <q-separator color="grey" />

    <div class="row q-px-lg q-pt-md justify-start">
      <div class="q-pb-md text-h6 col-6">
        Detalhes do produto
      </div>
    </div>

    <div class="row q-px-lg q-py-md justify-center q-gutter-sm">
      <q-card
        class="bg-grey-3 q-pa-sm col-xs-12 col-sm-5"
        flat
      >
        <div class="text-body2">
          {{ description }}
        </div>
      </q-card>

      <q-card class="col-xs-12 col-sm-6">
        <q-list>
          <q-item>
            <q-item-section>
              <q-item-label>Idioma</q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-item-label>{{ languages }}</q-item-label>
            </q-item-section>
          </q-item>

          <q-separator
            spaced
            inset
            color="grey"
          />

          <q-item>
            <q-item-section>
              <q-item-label>Categoria</q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-item-label>{{ category }}</q-item-label>
            </q-item-section>
          </q-item>

          <q-separator
            spaced
            inset
            color="grey"
          />

          <q-item>
            <q-item-section>
              <q-item-label>Sistema operacional</q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-item-label>{{ os }}</q-item-label>
            </q-item-section>
          </q-item>

          <q-separator
            spaced
            inset
            color="grey"
          />

          <q-item>
            <q-item-section>
              <q-item-label>Vendido por</q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-item-label>{{ store }}</q-item-label>
            </q-item-section>
          </q-item>

          <q-separator
            spaced
            inset
            color="grey"
          />
        </q-list>
      </q-card>
    </div>

    <q-separator color="grey" />

    <div class="q-px-lg q-py-md">
      <Comments
        :product-id="productId"
      />
    </div>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'
import Comments from 'src/components/Comments.vue'

export default defineComponent({

  name: 'Product',
  components: { Comments },
  setup () {
    return {
      productName: ref(''),
      languages: ref(''),
      slide: ref(1),
      version: ref(''),
      price: ref(0),
      store: ref(''),
      os: ref(''),
      category: ref(''),
      ratingAverage: ref(0),
      ratingQtd: ref(0),
      ratings: ref([]),
      productId: ref(null),
      loading: ref(false),
      description: ref(''),
      imgs: ref([]),
      imgUrl: ref(process.env.API + '/storage')
    }
  },
  created () {
    this.productId = this.$route.params.id
    this.getProductDetails()
    this.getProductRatings()
  },
  methods: {
    async getProductRatings () {
      api.get(`/ratings/product/${this.productId}`)
        .then((response) => {
          this.ratings = response.data.ratings
          this.ratingQtd = this.ratings.length
          if (this.ratings.length > 0) {
            let ratingAverage = 0
            for (let c = 0; c < this.ratings.length; c++) {
              ratingAverage += this.ratings[c].rating
            }
            this.ratingAverage = ratingAverage / this.ratings.length
          }
        })
        .catch((error) => {
          console.error('erro message: ' + error.message)
        })
    },
    async getProductDetails () {
      api.get(`/product/${this.productId}`)
        .then((response) => {
          this.productName = response.data.product.name
          this.languages = response.data.product.language.name
          this.version = response.data.product.version
          this.price = response.data.product.price
          this.category = response.data.product.category.name
          this.store = response.data.product.store.name
          this.os = response.data.product.os.name
          this.description = response.data.product.description
          this.imgs = response.data.product.images
          for (let c = 0; c < this.imgs.length; c++) {
            this.imgs[c].order = c + 1
          }
        })
        .catch((error) => {
          console.error('erro encontrado: ' + error.message)
        })
        .finally(() => {
          this.loading = false
        })
    },

    async addToCart () {
      let cart = this.$q.localStorage.getItem('cart')
      let hasCartProduct = false
      if (cart) {
        cart.forEach(product => {
          if (product.id === this.productId) {
            hasCartProduct = true
          }
        })

        if (hasCartProduct === true) {
          this.showMessage('Produto já foi adicionado ao carrinho', 'warning', 'warning')
        } else {
          cart.push({ id: this.productId, name: this.productName, price: this.price })
          this.$q.localStorage.set('cart', cart)
          this.showMessage('Produto adicionado ao carrinho', 'positive', 'check_circle')
        }
      } else {
        cart = [{ id: this.productId, name: this.productName, price: this.price }]
        this.$q.localStorage.set('cart', cart)
        this.showMessage('Produto adicionado ao carrinho', 'positive', 'check_circle')
      }

      window.dispatchEvent(new CustomEvent('modify-cart', {
        detail: {
          productQtd: cart.length
        }
      }))
    },
    showMessage (msg, color, icon) {
      this.$q.notify({
        message: msg,
        color: color,
        icon: icon
      })
    }
  }
})
</script>

<style>

</style>
