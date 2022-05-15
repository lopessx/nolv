<template>
  <q-page>
    <div class="row q-px-xl q-pt-lg q-pb-md q-gutter-sm">
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

          <template #control>
            <q-carousel-control
              position="top-right"
              :offset="[18, 18]"
              class="text-white rounded-borders"
              style="background: rgba(0, 0, 0, .3); padding: 4px 8px;"
            >
              <q-btn
                color="accent"
                icon="close"
                round
                @click="deleteImage()"
              />
            </q-carousel-control>
          </template>
        </q-carousel>
      </div>
      <div class="col-5">
        <q-input
          v-model="description"
          filled
          type="textarea"
          label="Descrição do produto"
        />
      </div>
    </div>

    <q-separator color="grey" />

    <div class="row justify-center q-py-md">
      <div class="col-xs-12 col-sm-5">
        <q-uploader
          ref="imageUploader"
          :url="urlUpload + '/product/image/upload'"
          label="Upload de imagens"
          multiple
          hide-upload-btn
          :form-fields="[{name: 'productId', value: productId}]"
          style="max-width: 280px"
        />
      </div>
      <div class="col-xs-12 col-sm-5">
        <q-uploader
          ref="fileUploader"
          :url="urlUpload + '/product/upload'"
          label="Upload do produto"
          multiple
          hide-upload-btn
          :form-fields="[{name: 'productId', value: productId}, {name: 'productName', value: productName}]"
          style="max-width: 280px"
        />
      </div>
    </div>

    <q-separator color="grey" />

    <div class="row col-12 q-px-xl q-py-md">
      <div class="col-6">
        <q-input
          v-model="productName"
          label="Nome do produto"
        />
        <q-input
          v-model="version"
          prefix="v"
          label="Versão"
        />
      </div>
      <div class="row col-6 justify-center">
        <q-input
          v-model="price"
          class="col-6"
          prefix="R$"
          label="Preço"
          mask="#,##"
          fill-mask="0"
          reverse-fill-mask
          input-class="text-right"
        />
      </div>
    </div>

    <q-separator color="grey" />

    <div class="row q-px-lg q-py-md justify-center">
      <q-list class="col-12">
        <q-item>
          <q-item-section>
            <q-item-label>Idioma</q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-item-label>
              <div class="row">
                <q-select
                  v-model="language"
                  style="min-width: 150px;"
                  class="col-12"
                  filled
                  :options="languageOptions"
                  label="Idioma"
                  required
                  emit-value
                  map-options
                />
              </div>
            </q-item-label>
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
            <q-item-label>
              <q-select
                v-model="category"
                filled
                style="min-width: 150px;"
                :options="categoryOptions"
                label="Categoria"
                required
                emit-value
                map-options
              />
            </q-item-label>
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
            <q-select
              v-model="os"
              style="min-width: 150px;"
              filled
              :options="osOptions"
              label="Sistema operacional"
              required
              emit-value
              map-options
            />
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

    <div class="row q-px-xl q-py-lg justify-center items-center q-gutter-sm">
      <q-btn
        color="negative"
        label="Deletar"
        @click="deleteProduct"
      />
      <q-btn
        color="accent"
        label="Salvar"
        @click="editProduct"
      />
    </div>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'ProductEdit',
  setup () {
    return {
      urlUpload: ref(process.env.API),
      productName: ref(''),
      languages: ref(''),
      slide: ref(1),
      version: ref(''),
      price: ref(0),
      os: ref(''),
      language: ref(''),
      category: ref(''),
      languageOptions: ref([]),
      categoryOptions: ref([]),
      osOptions: ref([]),
      productId: ref(null),
      store: ref(null),
      imgs: ref([]),
      imgUrl: ref(process.env.API + '/storage'),
      description: ref('')
    }
  },
  mounted () {
    this.productId = this.$route.params.id
    this.getLanguages()
    this.getOs()
    this.getCategories()
    this.getProductDetails()
    this.price = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(this.price)
    this.price = this.price.replace(/R\$/gm, '')
    this.price = this.price.replace(/\s/gm, '')
  },
  methods: {
    async getProductDetails () {
      api.get(`product/${this.productId}`)
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
    getLanguages () {
      api.get('languages')
        .then((response) => {
          if (response.data.success) {
            response.data.languages.forEach(language => {
              this.languageOptions.push(language)
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
      api.get('os')
        .then((response) => {
          if (response.data.success) {
            response.data.operational_systems.forEach(os => {
              this.osOptions.push(os)
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
      api.get('categories')
        .then((response) => {
          if (response.data.success) {
            response.data.categories.forEach(category => {
              this.categoryOptions.push(category)
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
    deleteImage () {
      console.log('deletar imagem: ' + this.slide + ' imagem: ' + JSON.stringify(this.imgs[this.slide - 1]))
      this.$q.dialog({
        title: 'Tem certeza que deseja deletar essa imagem?',
        message: 'Essa ação será irreversível.',
        cancel: {
          label: 'Cancelar',
          color: 'negative'
        },
        ok: {
          label: 'Confirmar',
          color: 'accent'
        },
        persistent: true
      }).onOk(() => {
        const imgId = this.imgs[this.slide - 1].id
        api.delete(`/product/image/delete/${imgId}`)
          .then((response) => {
            console.log('resposta exclusão: ' + JSON.stringify(response.data))
            this.showMessage('Falha na exclusão da imagem', 'positive', 'check_circle')
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Falha na exclusão da imagem', 'negative', 'error')
          })
      })
    },
    editProduct () {
      api.put(`/product/${this.productId}`, { categoryId: this.category, storeId: this.storeId, languageId: this.language, osId: this.os, name: this.productName, description: this.description, version: this.version, price: this.price })
        .then((response) => {
          if (response.data.success === true) {
            this.showMessage('Produto editado com sucesso', 'positive', 'check_circle')
          } else {
            this.showMessage('Erro ao editar produto', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('erro message: ' + error.message + ' code ' + error.code)
          this.showMessage('Erro ao editar produto', 'negative', 'error')
        })
        .finally(() => {
          if (this.productId) {
            this.$refs.imageUploader.upload()
            this.$refs.fileUploader.upload()
          }
        })
    },
    deleteProduct () {
      this.$q.dialog({
        title: 'Tem certeza que deseja deletar esse produto?',
        message: 'Essa ação será irreversível.',
        cancel: {
          label: 'Cancelar',
          color: 'negative'
        },
        ok: {
          label: 'Confirmar',
          color: 'accent'
        },
        persistent: true
      }).onOk(() => {
        api.delete(`/product/${this.productId}`)
          .then((response) => {
            console.log('produto deletado ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.showMessage('Produto deletado com sucesso', 'positive', 'check_circle')
              this.$router.push('/vendedor')
            } else {
              this.showMessage('Erro ao deletar produto', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.error('erro message: ' + error.message + ' code ' + error.code)
            this.showMessage('Erro ao deletar produto', 'negative', 'error')
          })
      })
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
