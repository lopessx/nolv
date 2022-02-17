<template>
  <q-page>
    <div class="row q-px-xl q-pt-lg q-pb-md q-gutter-xs">
      <div class="col-7">
        <q-carousel
          v-model="slide"
          animated
          navigation
          infinite
          arrows
          transition-prev="slide-right"
          transition-next="slide-left"
        >
          <q-carousel-slide
            :name="1"
            img-src="https://cdn.quasar.dev/img/mountains.jpg"
          />
          <q-carousel-slide
            :name="2"
            img-src="https://cdn.quasar.dev/img/parallax1.jpg"
          />
          <q-carousel-slide
            :name="3"
            img-src="https://cdn.quasar.dev/img/parallax2.jpg"
          />
          <q-carousel-slide
            :name="4"
            img-src="https://cdn.quasar.dev/img/quasar.jpg"
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
            v-model="ratingModel"
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
              color="accent"
              label="+ Carrinho"
            />
          </div>
        </div>
      </div>

      <q-list class="col-5">
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
    </div>

    <q-separator color="grey" />

    <div class="row q-px-xl q-py-md">
      <div class="text-h6">
        Comentários
      </div>
      <q-list class="col-12">
        <q-infinite-scroll
          :offset="250"
          @load="onLoad"
        >
          <div
            v-for="(item, index) in items"
            :key="index"
          >
            <q-item>
              <q-item-section>
                <q-item-label>Nome do cliente</q-item-label>
                <q-item-label caption>
                  Comentário completo. Lorem ipsum dolor sit amet, consectetur adipiscit elit.
                </q-item-label>
              </q-item-section>

              <q-item-section
                side
                top
              >
                <q-item-label caption>
                  12/10/2020
                </q-item-label>
                <div class="text-orange">
                  <q-rating
                    v-model="ratingModel"
                    color="accent"
                    readonly
                  />
                </div>
              </q-item-section>
            </q-item>

            <q-separator
              spaced
              inset
            />
          </div>
          <template #loading>
            <div class="row justify-center q-my-md">
              <q-spinner-dots
                color="primary"
                size="40px"
              />
            </div>
          </template>
        </q-infinite-scroll>
      </q-list>
    </div>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'Product',
  setup () {
    const items = ref([{}, {}, {}])

    return {
      productName: ref(''),
      languages: ref(''),
      slide: ref(1),
      version: ref('1.0.0'),
      price: ref(0),
      store: ref(''),
      os: ref(''),
      category: ref(''),
      ratingModel: ref(2.5),
      ratingQtd: ref(120),
      productId: ref(null),
      loading: ref(false),
      items,
      description: ref(''),
      onLoad (index, done) {
        setTimeout(() => {
          items.value.push({}, {}, {}, {})
          done()
        }, 2000)
      }
    }
  },
  created () {
    console.log('nova página renderizada')
    console.log('para: ' + this.$route.params.id)
    this.productId = this.$route.params.id
    this.getProductDetails()
  },
  methods: {
    async getProductDetails () {
      api.get(`/product/${this.productId}`)
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
        })
        .catch((error) => {
          console.error('erro encontrado: ' + error.message)
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
})
</script>

<style>

</style>
