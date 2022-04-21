<template>
  <q-page>
    <q-form>
      <div class="row q-px-xl q-pt-lg q-gutter-sm">
        <div class="col-6">
          <q-uploader
            url="http://localhost:4444/upload"
            label="Upload de imagens"
            multiple
            style="max-width: 280px"
          />
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

      <div class="row col-12 q-px-xl q-py-md">
        <div class="col-6 q-pr-md">
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
        <div class="row col-6 justify-start">
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
          @click="newProduct()"
        />
      </div>
    </q-form>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'ProductRegister',
  setup () {
    return {
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
      description: ref('')
    }
  },
  mounted () {
    this.price = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(this.price)
    this.price = this.price.replace(/R\$/gm, '')
    this.price = this.price.replace(/\s/gm, '')
    this.getLanguages()
    this.getOs()
    this.getCategories()
  },
  methods: {
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
    newProduct () {
      api.post('/product', {})
        .then((response) => {
          if (response.data.success) {
            console.log('sucesso')
          } else {
            this.showMessage('Erro ao salvar novo produto', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Erro ao salvar novo produto', 'negative', 'error')
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
