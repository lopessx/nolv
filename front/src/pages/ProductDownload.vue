<template>
  <q-page>
    <div class="row q-px-xl q-pt-lg q-gutter-sm">
      <div class="col-6">
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
            v-for="(image, id) in imgs"
            :key="id"
            :name="id + 1"
            :img-src="imgUrl + image.path"
          />
        </q-carousel>
      </div>
      <div class="col-5">
        <div class="text-body2">
          {{ description }}
        </div>
      </div>
    </div>

    <div class="row col-12 q-px-xl q-py-md">
      <div class="col-6">
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
      <div class="row col-6 justify-center items-center">
        <div class="col-6 justify-center">
          <q-btn
            color="accent"
            label="Baixar"
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

    <div class="row q-px-xl q-py-md justify-center">
      <q-list class="col-7">
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
      </q-list>
    </div>

    <q-separator color="grey" />

    <div class="q-px-xl q-py-md">
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
    // TODO make a request to img to see if exists
    const client = this.$q.localStorage.getItem('client')

    if (client) {
      this.clientId = client.id
    }

    this.productId = this.$route.params.id
    this.getProductDetails()

    console.log('category options: ' + JSON.stringify(this.categoryOptions) + ' category ' + this.category)

    // this.getRatings()
  },
  methods: {
    async getProductDetails () {
      api.get(`/product/${this.productId}`)
        .then((response) => {
          console.log('resposta: ' + JSON.stringify(response.data))
          this.productName = response.data.product.name
          this.language = response.data.product.language_id
          this.version = response.data.product.version
          this.price = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(response.data.product.price)
          this.category = response.data.product.category_id
          this.store = response.data.product.store_name
          this.os = response.data.product.operational_system_id
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
