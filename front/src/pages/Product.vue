<template>
  <q-page>
    <div class="row q-px-xl q-pt-lg q-pb-md q-gutter-xs">
      <div class="col-7">
        <q-carousel
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
      </div>
      <div class="col-4">
        <div class="text-body2">
          {{ description }}
        </div>
      </div>
    </div>

    <q-separator color="grey" />

    <div class="row col-12 q-px-xl q-py-md">
      <div class="row col-7">
        <div class="col-6 q-pr-lg">
          <div class="text-h5">
            {{ productName }}
          </div>
          <div class="text-subtitle1">
            v {{ version }}
          </div>
          <q-rating
            v-model="ratingAverage"
            readonly
            size="2em"
            :max="5"
            color="accent"
          />
          {{ ratingQtd }}
        </div>
        <div class="col-5">
          <div class="text-h6 text-accent text-weight-bold">
            {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(price) }}

            <q-btn
              :loading="loading"
              color="accent"
              label="+ Carrinho"
              @click="addToCart"
            />
          </div>
        </div>
      </div>

      <q-card class="col-5">
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
        </q-list>
      </q-card>
    </div>

    <q-separator color="grey" />

    <div class="q-px-xl q-py-md">
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
      version: ref('1.0.0'),
      price: ref(0),
      store: ref(''),
      os: ref(''),
      category: ref(''),
      ratingAverage: ref(0),
      ratingQtd: ref(120),
      ratings: ref([]),
      productId: ref(null),
      loading: ref(false),
      description: ref(''),
      imgs: ref([]),
      imgUrl: ref(process.env.API + '/storage')
    }
  },
  created () {
    console.log('nova página renderizada')
    console.log('para: ' + this.$route.params.id)
    this.productId = this.$route.params.id
    this.getProductDetails()
    this.getProductRatings()
  },
  methods: {
    async getProductRatings () {
      api.get(`ratings/product/${this.productId}`)
        .then((response) => {
          this.ratings = response.data.ratings
          console.log('ratings: ' + JSON.stringify(this.ratings))
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
      api.get(`product/${this.productId}`)
        .then((response) => {
          console.log('resposta: ' + JSON.stringify(response.data))
          this.productName = response.data.product.name
          this.languages = response.data.product.language_name
          this.version = response.data.product.version
          this.price = response.data.product.price
          this.category = response.data.product.category_name
          this.store = response.data.product.store_name
          this.os = response.data.product.os_name
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

    async storeComment (rating) {
      this.loading = true
      console.log('salvou comentário novo: ' + JSON.stringify(rating))
      api.post('rating', { product_id: rating.product_id, rating: rating.rating, comment: rating.comment, client_id: rating.client_id })
        .then((response) => {
          console.log('resposta ' + JSON.stringify(response.data))
          const newRating = response.data.rating
          // TODO change static name to session name
          newRating.client = { name: 'João Silva' }
          this.ratings.push(newRating)
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
          console.error('erro encontrado: ' + error.message)
        })
        .finally(() => {
          this.loading = false
        })
    },

    async addToCart () {
      // TODO do not add duplicate items
      console.log('adicionado ao carrinho')
      let cart = this.$q.localStorage.getItem('cart')
      if (cart) {
        cart.push({ id: this.productId, name: this.productName, price: this.price })
        this.$q.localStorage.set('cart', cart)
      } else {
        cart = [{ id: this.productId, name: this.productName, price: this.price }]
        this.$q.localStorage.set('cart', cart)
      }

      window.dispatchEvent(new CustomEvent('modify-cart', {
        detail: {
          productQtd: cart.length
        }
      }))
    }
  }
})
</script>

<style>

</style>
