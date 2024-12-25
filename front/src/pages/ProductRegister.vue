<template>
  <q-page>
    <q-form
      ref="formProduct"
      @submit.prevent
    >
      <div class="row q-px-xl q-py-lg q-gutter-sm justify-start">
        <div class="col-xs-12 col-sm-6 text-h5">
          Cadastrar novo produto
        </div>
      </div>

      <div class="row justify-center q-py-md q-gutter-sm">
        <q-uploader
          ref="imageUploader"
          :url="urlUpload + '/product/image/upload'"
          label="Upload de imagens"
          multiple
          hide-upload-btn
          :form-fields="[{name: 'productId', value: productId}]"
          accept=".jpg, image/*"
          style="max-width: 280px"
          :headers="[{name: 'Authorization', value: 'bearer ' + $q.cookies.get('authKey')}]"
          @added="imgs => validateImages(imgs)"
          @finish="showSuccessMessage()"
        />
        <q-uploader
          ref="fileUploader"
          :url="urlUpload + '/product/upload'"
          label="Upload do produto"
          multiple
          hide-upload-btn
          :form-fields="[{name: 'productId', value: productId}, {name: 'productName', value: productName}]"
          style="max-width: 280px"
          max-file-size="204800000"
          accept=".zip"
          :headers="[{name: 'Authorization', value: 'bearer ' + $q.cookies.get('authKey')}]"
          @added="files => validateFiles(files)"
          @finish="uploadImages()"
        />
      </div>

      <q-separator color="grey" />

      <div class="row col-12 q-pa-md q-gutter-md justify-center">
        <div class="col-xs-11 col-sm-5">
          <q-input
            v-model="productName"
            label="Nome do produto"
            required
            lazy-rules
            :rules="[required]"
            :readonly="loading"
          />
          <q-input
            v-model="version"
            prefix="v"
            label="Versão"
            required
            lazy-rules
            :rules="[required]"
            :readonly="loading"
          />
        </div>

        <div class="col-xs-11 col-sm-4">
          <q-input
            v-model="price"
            prefix="R$"
            label="Preço"
            type="tel"
            mask="#,##"
            fill-mask="0"
            reverse-fill-mask
            input-class="text-right"
            required
            lazy-rules
            :rules="[required, priceValidation]"
            :readonly="loading"
          />
        </div>
        <div class="col-xs-12 col-sm-11 q-py-md">
          <q-input
            v-model="description"
            filled
            type="textarea"
            label="Descrição do produto"
            required
            lazy-rules
            :rules="[required]"
            :readonly="loading"
          />
        </div>
      </div>

      <q-separator color="grey" />

      <div class="row q-pa-md justify-center">
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
                    :readonly="loading"
                    lazy-rules
                    :rules="[required]"
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
                  :readonly="loading"
                  lazy-rules
                  :rules="[required]"
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
                :readonly="loading"
                lazy-rules
                :rules="[required]"
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

      <div class="row q-px-xl q-py-md justify-center items-center">
        <q-btn
          color="accent"
          label="Salvar"
          :loading="loading"
          @click="newProduct()"
        />
      </div>
    </q-form>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'
import { required, priceValidation } from 'src/utils/validations'

export default defineComponent({
  name: 'ProductRegister',
  setup () {
    return {
      loading: ref(false),
      productName: ref(''),
      languageOptions: ref([]),
      drawer: ref(false),
      miniState: ref(true),
      version: ref(''),
      price: ref(0),
      os: ref(null),
      language: ref(null),
      category: ref(null),
      categoryOptions: ref([]),
      osOptions: ref([]),
      description: ref(''),
      storeId: ref(''),
      clientId: ref(''),
      productId: ref(''),
      urlUpload: ref(process.env.API),
      hasImg: ref(false),
      hasFile: ref(false)
    }
  },
  mounted () {
    this.loading = true
    const client = this.$q.localStorage.getItem('client')

    if (client) {
      this.clientId = client.id
    }

    this.price = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(this.price)
    this.price = this.price.replace(/R\$/gm, '')
    this.price = this.price.replace(/\s/gm, '')

    this.getLanguages()
    this.getOs()
    this.getCategories()
    this.getStore()
  },
  methods: {
    getStore () {
      api.get(`/store/client/${this.clientId}`)
        .then((response) => {
          if (response.data.success === true) {
            this.storeId = response.data.store.id
          } else {
            this.showMessage('Nenhuma loja encontrada', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Nenhuma loja encontrada', 'negative', 'error')
        })
        .finally(() => {
          this.loading = false
        })
    },
    getLanguages () {
      api.get('/languages')
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
      api.get('/os')
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
      api.get('/categories')
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
    newProduct () {
      this.loading = true
      this.$refs.formProduct.validate(false).then(outcome => {
        if (outcome === true && this.hasImg === true && this.hasFile === true) {
          api.post('/product', { categoryId: this.category.value, storeId: this.storeId, languageId: this.language.value, osId: this.os.value, name: this.productName, description: this.description, version: this.version, price: this.price })
            .then((response) => {
              if (response.data.success === true) {
                this.productId = response.data.product.id
              } else {
                this.showMessage('Erro ao salvar novo produto', 'negative', 'error')
              }
            })
            .catch((error) => {
              this.loading = false
              console.error('message ' + error.message + ' code ' + error.code)
              this.showMessage('Erro ao salvar novo produto', 'negative', 'error')
            })
            .finally(() => {
              if (this.productId) {
                this.$refs.fileUploader.upload()
              }
            })
        } else {
          this.loading = false
          this.showMessage('Preencha todos os campos', 'warning', 'warning')
        }
      })
    },
    uploadImages () {
      this.$refs.imageUploader.upload()
    },
    showMessage (msg, color, icon) {
      this.$q.notify({
        message: msg,
        color: color,
        icon: icon
      })
    },
    validateFiles (files) {
      if (files.length > 0) {
        this.hasFile = true
      }
    },
    validateImages (imgs) {
      if (imgs.length > 0) {
        this.hasImg = true
      }
    },
    showSuccessMessage () {
      this.productId = ''
      this.productName = ''
      this.price = 0
      this.version = ''
      this.description = ''
      this.language = null
      this.os = null
      this.category = null
      this.$refs.imageUploader.removeUploadedFiles()
      this.$refs.fileUploader.removeUploadedFiles()
      this.$refs.formProduct.resetValidation()
      this.loading = false
      this.showMessage('Produto criado com sucesso', 'positive', 'check_circle')
    },
    required,
    priceValidation
  }
})
</script>

<style>

</style>
