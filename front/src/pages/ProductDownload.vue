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

    <div class="row col-12 q-px-lg q-py-md">
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
            v-model="ratingModel"
            readonly
            size="2em"
            :max="5"
            color="accent"
          />
          {{ ratingQtd }}
        </div>
      </div>
      <div class="row col-xs-12 col-sm-6 q-pb-md justify-end">
        <div class="row col-xs-12 col-sm-8 q-py-lg">
          <q-btn
            class="col-xs-12 col-sm-8"
            color="accent"
            label="Download"
            :loading="loading"
            @click="downloadProduct()"
          />
        </div>
      </div>
    </div>

    <div
      v-if="downloadProgress > 0"
      class="row q-pa-lg"
    >
      <q-linear-progress
        stripe
        size="25px"
        :value="downloadProgress"
        color="accent"
      >
        <div class="absolute-full flex flex-center">
          <q-badge
            color="white"
            text-color="accent"
            :label="downloadProgressLabel"
          />
        </div>
      </q-linear-progress>
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
        <q-item>
          <q-item-section>
            <q-item-label>Idioma</q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-item-label>{{ language }}</q-item-label>
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
      </q-card>
    </div>

    <q-separator color="grey" />

    <div class="q-px-lg q-py-md">
      <Comments
        :enable-comment="true"
        :product-id="productId"
        :client-id="clientId.toString()"
        @update="updateRating"
      />
    </div>
  </q-page>
</template>

<script>
import { api, download } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'
import Comments from 'src/components/Comments.vue'

export default defineComponent({
  name: 'ProductDownload',
  components: { Comments },
  setup () {
    return {
      loading: ref(false),
      downloadProgress: ref(0),
      downloadProgressLabel: ref(0),
      productId: ref(''),
      clientId: ref(''),
      languageOptions: ref([]),
      categoryOptions: ref([]),
      osOptions: ref([]),
      ratingModelClient: ref(0),
      commentInput: ref(''),
      comments: ref([]),
      description: ref(''),
      productName: ref(''),
      language: ref(''),
      category: ref(''),
      os: ref(''),
      slide: ref(1),
      version: ref(''),
      price: ref(0),
      store: ref(''),
      ratingModel: ref(0),
      ratingQtd: ref(0),
      imgs: ref([]),
      imgUrl: ref(process.env.API + '/storage'),
      file: ref(null)
    }
  },
  created () {
    this.loading = true
    const client = this.$q.localStorage.getItem('client')

    if (client) {
      this.clientId = client.id
    }

    this.productId = this.$route.params.id
    this.getProductDetails()

    console.log('category options: ' + JSON.stringify(this.categoryOptions) + ' category ' + this.category)
  },
  methods: {
    async getProductDetails () {
      api.get(`/product/${this.productId}`)
        .then((response) => {
          console.log('resposta: ' + JSON.stringify(response.data))
          this.productName = response.data.product.name
          this.language = response.data.product.language.id
          this.version = response.data.product.version
          this.price = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(response.data.product.price)
          this.category = response.data.product.category.id
          this.store = response.data.product.store.name
          this.os = response.data.product.os.id
          this.description = response.data.product.description
          this.imgs = response.data.product.images
          const fileName = response.data.product.file_path

          const fileNameSplit = fileName.split('.')

          this.file = { name: fileNameSplit[0], ext: fileNameSplit[1], completeName: fileName }

          for (let c = 0; c < this.imgs.length; c++) {
            this.imgs[c].order = c + 1
          }
        })
        .catch((error) => {
          console.error('erro encontrado: ' + error.message)
        })
        .finally(() => {
          this.loading = false
          this.getLanguages()
          this.getOs()
          this.getCategories()
        })
    },
    getLanguages () {
      api.get('/languages')
        .then((response) => {
          if (response.data.success) {
            response.data.languages.forEach(language => {
              if (language.value === this.language) {
                this.language = language.label
              }
            })
          } else {
            this.showMessage('Nenhum idioma encontrado', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Nenhum idioma encontrado', 'negative', 'error')
        })
    },
    getOs () {
      api.get('/os')
        .then((response) => {
          if (response.data.success) {
            response.data.operational_systems.forEach(os => {
              if (os.value === this.os) {
                this.os = os.label
              }
            })
          } else {
            this.showMessage('Nenhum sistema operacional encontrado', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Nenhum sistema operacional encontrado', 'negative', 'error')
        })
    },
    getCategories () {
      api.get('/categories')
        .then((response) => {
          if (response.data.success) {
            response.data.categories.forEach(category => {
              if (category.value === this.category) {
                this.category = category.label
              }
            })
          } else {
            this.showMessage('Nenhuma categoria encontrada', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Nenhuma categoria encontrada', 'negative', 'error')
        })
    },
    downloadProduct () {
      this.loading = true
      this.downloadProgress = parseInt(5 / 100)
      download.get(`/product/download/${this.productId}`, {
        onDownloadProgress: progressEvent => {
          const percentCompleted = (parseInt((progressEvent.loaded / progressEvent.total) * 100) / 100)
          this.downloadProgress = percentCompleted
          this.downloadProgressLabel = parseInt(percentCompleted * 100)
          console.log('completed: ', percentCompleted)
        }
      })
        .then((response) => {
          console.log('FINALIZADO')
          const url = window.URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')
          link.href = url
          link.setAttribute('download', this.file.completeName) // or any other extension
          document.body.appendChild(link)
          link.click()
          this.showMessage('Download realizado com sucesso', 'positive', 'check_circle')
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Download falhou', 'negative', 'error')
        })
        .finally(() => {
          this.loading = false
        })
    },
    updateRating (newRating) {
      console.log('atualizar rating ' + newRating)
      this.ratingModel = newRating.ratingAvg
      this.ratingQtd = newRating.ratingQtd
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
