<template>
  <q-page>
    <q-form
      ref="formProduct"
      @submit.prevent
    >
      <div class="row q-px-lg q-pt-lg q-pb-md q-gutter-sm justify-center">
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

      <div class="row justify-center q-py-md q-gutter-sm">
        <q-uploader
          ref="imageUploader"
          :url="urlUpload + '/product/image/upload'"
          label="Upload de imagens"
          multiple
          hide-upload-btn
          :form-fields="[{name: 'productId', value: productId}]"
          style="max-width: 280px"
          accept=".jpg, image/*"
          :headers="[{name: 'Authorization', value: 'bearer ' + $q.cookies.get('authKey')}]"
          @finish="reloadImages()"
        />

        <q-uploader
          ref="fileUploader"
          :url="urlUpload + '/product/upload'"
          label="Upload do produto"
          multiple
          hide-upload-btn
          :form-fields="[{name: 'productId', value: productId}, {name: 'productName', value: productName}]"
          style="max-width: 280px"
          max-file-size="2048000000"
          accept=".zip"
          :headers="[{name: 'Authorization', value: 'bearer ' + $q.cookies.get('authKey')}]"
          @finish="resetValidations()"
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
                  emit-value
                  map-options
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
                emit-value
                map-options
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
    </q-form>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'
import { required, priceValidation } from 'src/utils/validations'

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
      description: ref(''),
      hasFile: ref(false),
      hasImg: ref(false),
      loading: ref(false)
    }
  },
  async mounted () {
    this.loading = true
    this.productId = this.$route.params.id

    const langResult = await this.getLanguages()
    const osResult = await this.getOs()
    const categoryResult = await this.getCategories()

    if (langResult === true && osResult === true && categoryResult === true) {
      this.getProductDetails()
    }

    this.price = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(this.price)
    this.price = this.price.replace(/R\$/gm, '')
    this.price = this.price.replace(/\s/gm, '')
  },
  methods: {
    async getProductDetails () {
      this.loading = true

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
        })
        .catch((error) => {
          console.error('erro encontrado: ' + error.message)
        })
        .finally(() => {
          this.loading = false
        })
    },
    async getLanguages () {
      const result = await api.get('/languages')
        .then((response) => {
          if (response.data.success === true) {
            response.data.languages.forEach(language => {
              this.languageOptions.push(language)
            })

            return true
          } else {
            this.showMessage('Nenhum idioma encontrado', 'negative', 'error')

            return false
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Nenhum idioma encontrado', 'negative', 'error')

          return false
        })

      return new Promise(function (resolve, reject) {
        if (result === true) {
          resolve(true)
        } else {
          resolve(false)
        }
      })
    },
    async getOs () {
      const result = await api.get('/os')
        .then((response) => {
          if (response.data.success) {
            response.data.operational_systems.forEach(os => {
              this.osOptions.push(os)
            })

            return true
          } else {
            this.showMessage('Nenhum sistema operacional encontrado', 'negative', 'error')

            return false
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Nenhum sistema operacional encontrado', 'negative', 'error')

          return false
        })

      return new Promise(function (resolve, reject) {
        if (result === true) {
          resolve(true)
        } else {
          resolve(false)
        }
      })
    },
    async getCategories () {
      const result = await api.get('/categories')
        .then((response) => {
          if (response.data.success) {
            response.data.categories.forEach(category => {
              this.categoryOptions.push(category)
            })

            return true
          } else {
            this.showMessage('Nenhuma categoria encontrada', 'negative', 'error')

            return false
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Nenhuma categoria encontrada', 'negative', 'error')

          return false
        })

      return new Promise(function (resolve, reject) {
        if (result === true) {
          resolve(true)
        } else {
          resolve(false)
        }
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
            this.showMessage('Imagem excluída com sucesso', 'positive', 'check_circle')
            this.getProductDetails()
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Falha na exclusão da imagem', 'negative', 'error')
          })
      })
    },
    editProduct () {
      this.loading = true
      this.$refs.formProduct.validate(false).then(outcome => {
        if (outcome === true) {
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
                this.$refs.fileUploader.upload()
                this.$refs.imageUploader.upload()
              }
            })
        } else {
          this.loading = false
          this.showMessage('Preencha todos os campos', 'warning', 'warning')
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
    validateFiles (files) {
      console.log('files ' + JSON.stringify(files))
      if (files.length > 0) {
        this.hasFile = true
      }
    },
    validateImages (imgs) {
      console.log('imgs ' + JSON.stringify(imgs))
      if (imgs.length > 0) {
        this.hasImg = true
      }
    },
    resetValidations () {
      this.$refs.imageUploader.removeUploadedFiles()
      this.$refs.fileUploader.removeUploadedFiles()
      this.$refs.formProduct.resetValidation()
      this.loading = false
    },
    reloadImages () {
      this.$refs.imageUploader.removeUploadedFiles()
      this.$refs.fileUploader.removeUploadedFiles()
      this.$refs.formProduct.resetValidation()
      this.getProductDetails()
    },
    showMessage (msg, color, icon) {
      this.$q.notify({
        message: msg,
        color: color,
        icon: icon
      })
    },
    required,
    priceValidation
  }
})
</script>

<style>

</style>
