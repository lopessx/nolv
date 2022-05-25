<template>
  <q-page>
    <q-form class="row q-px-md q-pt-xl q-pb-md justify-start align-center">
      <div class="row col-12 justify-center text-center">
        <q-card
          class="col-xs-12 col-sm-10"
          style="max-width: 500px;"
        >
          <q-card-section>
            <div class="text-body1 text-weight-bold q-px-md">
              <q-input
                ref="storeNameInput"
                v-model="storeName"
                outline
                :readonly="hasStore"
                label="Nome da loja"
                required
                lazy-rules
                :rules="[required]"
              />
            </div>
          </q-card-section>
          <q-separator />
          <div
            v-if="hasStore"
            class="row justify-center align-center"
          >
            <div class="col-6 q-pa-md">
              <span class="text-h5 text-accent text-weight-bold">
                Saldo
              </span>
            </div>
            <div class="col-6 subtitle-1 text-weight-bold q-pa-md text-grey-7">
              <div class="text-h5 text-accent text-weight-bold">
                {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(balance) }}
              </div>
            </div>
          </div>
          <div
            v-if="!hasStore"
            class="row justify-end align-center"
          >
            <div
              v-if="!inputLocked"
              class="col-6 q-pa-md"
            >
              <q-btn
                color="accent"
                label="Cadastrar"
                @click="newStore()"
              />
            </div>
            <div
              v-if="inputLocked"
              class="col-6 q-pa-md"
            >
              <q-btn
                color="negative"
                label="Cancelar"
                @click="lockInputs()"
              />
            </div>
            <div
              v-if="inputLocked"
              class="col-6 q-pa-md"
            >
              <q-btn
                color="accent"
                label="Salvar"
                @click="editStore()"
              />
            </div>
          </div>
        </q-card>
      </div>
      <div
        v-if="hasStore"
        class="row col-12 justify-center align-center content-center"
      >
        <div class="row justify-center align-center q-pa-md q-gutter-md">
          <q-btn
            color="negative"
            label="Deletar loja"
            @click="deleteStore()"
          />
          <q-btn
            color="accent"
            label="Editar nome da loja"
            @click="unlockInputs()"
          />
        </div>
      </div>
    </q-form>
    <q-separator />
    <div class="row q-px-md q-py-md">
      <div class="col-12">
        <div class="col-12 q-pa-md q-gutter-sm">
          <q-table
            color="grey-8"
            grid
            :rows="productsList"
            :columns="columns"
            row-key="id"
            :filter="filter"
            :rows-per-page-options="productsPerPage"
            no-data-label="Nenhum produto encontrado"
            hide-header
          >
            <template #top-right>
              <div class="row q-gutter-md justify-center align-center">
                <q-input
                  v-model="filter"
                  filled
                  dense
                  debounce="300"
                  placeholder="Pesquisar..."
                />
                <q-btn
                  color="accent"
                  label="Adicionar Produto"
                  to="/produto/cadastro"
                />
              </div>
            </template>

            <template #top-left>
              <div class="row align-center justify-center q-gutter-sm q-py-sm">
                <q-icon
                  name="shopping_basket"
                  color="accent"
                  size="sm"
                />
                <div class="text-subtitle1">
                  Meus produtos
                </div>
              </div>
            </template>

            <template #item="props">
              <div class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3 grid-style-transition">
                <q-card
                  clickable
                  class="cursor-pointer q-hoverable"
                  @click="selectProduct(props.cols[0].value)"
                >
                  <q-card-section>
                    <q-img
                      :src="imgUrl + props.cols[7].value"
                      spinner-color="black"
                      style="height: 150px; max-width: auto;"
                    >
                      <template #error>
                        <div class="absolute-full flex flex-center bg-white text-white">
                          <q-img
                            src="../assets/placeholder.png"
                            spinner-color="black"
                            style="height: 150px; max-width: auto;"
                          />
                        </div>
                      </template>
                    </q-img>
                  </q-card-section>
                  <q-separator />
                  <q-card-section>
                    <div class="text-body1 text-weight-bold">
                      {{ props.cols[1].value }}
                    </div>
                  </q-card-section>
                  <q-separator />
                  <div class="subtitle-1 text-weight-bold q-pa-md text-grey-7">
                    {{ props.cols[3].value }}
                  </div>
                </q-card>
              </div>
            </template>
          </q-table>
        </div>
      </div>
      <q-separator />
      <div>
        <div />
        <div />
      </div>
    </div>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'
import { required } from 'src/utils/validations'

const columns = [
  { name: 'id', required: true, label: 'Identificador', field: 'id', format: val => `${val}` },
  { name: 'name', required: true, label: 'Nome', field: 'name', format: val => `${val}` },
  { name: 'desc', required: true, label: 'Produto', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'price', label: 'Preço (R$)', field: 'price', format: val => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val), sortable: true },
  { name: 'language', required: true, label: 'Idioma', field: 'language', format: val => `${val}`, sortable: true },
  { name: 'category', required: true, label: 'Categoria', field: 'category', format: val => `${val}`, sortable: true },
  { name: 'os', required: true, label: 'Sistema operacional', field: 'os', format: val => `${val}`, sortable: true },
  { name: 'image', required: false, label: 'Imagem', field: 'image', format: val => `${val}`, sortable: false }
]

export default defineComponent({
  name: 'VendorArea',
  setup () {
    return {
      hasStore: ref(true),
      storeName: ref(''),
      storeId: ref(''),
      email: ref(''),
      productsList: ref([]),
      balance: ref(0),
      drawer: ref(false),
      miniState: ref(true),
      username: ref(''),
      clientId: ref(''),
      filter: ref(''),
      inputLocked: ref(false),
      columns,
      productsPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0]),
      imgUrl: ref(process.env.API + '/storage/')
    }
  },

  mounted () {
    const client = this.$q.localStorage.getItem('client')

    if (client) {
      this.email = client.email
      this.username = client.name
      this.clientId = client.id
    }

    this.getClientStore()
  },

  methods: {
    async getClientStore () {
      api.get(`/store/client/${this.clientId}`)
        .then((response) => {
          if (response.data.success === true) {
            const products = response.data.products

            this.storeId = response.data.store.id
            this.storeName = response.data.store.name
            this.balance = response.data.store.balance

            if (products && products.length > 0) {
              for (let c = 0; c < products.length; c++) {
                const product = {}
                product.id = response.data.products[c].id
                product.name = response.data.products[c].name
                product.language = response.data.products[c].language_id
                product.category = response.data.products[c].category_id
                product.os = response.data.products[c].operational_system_id
                product.price = response.data.products[c].price
                product.image = response.data.products[c].main_image_path
                this.productsList.push(product)
              }
            }
          } else {
            this.hasStore = false
          }
        })
        .catch((error) => {
          console.error('error message ' + error.message + ' code ' + error.code)
        })
    },
    newStore () {
      this.$refs.storeNameInput.validate()

      if (this.$refs.storeNameInput.hasError) {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.post('/store', { clientId: this.clientId, name: this.storeName })
          .then((response) => {
            console.log('resposta ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.storeId = response.data.store.id
              this.hasStore = true
            } else {
              this.showMessage('Erro ao cadastrar loja', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Erro ao cadastrar loja', 'negative', 'error')
          })
      }
    },
    unlockInputs () {
      this.hasStore = false
      this.inputLocked = true
      this.$refs.storeNameInput.focus()
    },
    lockInputs () {
      this.hasStore = true
      this.inputLocked = false
    },
    editStore () {
      this.$refs.storeNameInput.validate()

      if (this.$refs.storeNameInput.hasError) {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.put(`/store/${this.storeId}`, { clientId: this.clientId, name: this.storeName })
          .then((response) => {
            console.log('resposta ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.storeId = response.data.store.id
              this.hasStore = true
            } else {
              this.showMessage('Erro ao cadastrar loja', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Erro ao cadastrar loja', 'negative', 'error')
          })
      }
    },
    deleteStore () {
      this.$q.dialog({
        title: 'Tem certeza que deseja apagar sua loja?',
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
        console.log('loja a ser deletada: ' + this.storeId)
        api.delete(`/store/${this.storeId}`)
          .then((response) => {
            if (response.data.success === true) {
              this.hasStore = false
              this.storeId = null
              this.products = []
              this.showMessage('Loja deletada com sucesso', 'positive', 'check_circle')
            } else {
              this.showMessage('Erro ao deletar loja', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Erro ao deletar loja', 'negative', 'error')
          })
      })
    },
    selectProduct (productId) {
      console.log('produto selecionado ' + JSON.stringify(productId))
      this.$router.push(`/produto/editar/${productId}`)
    },
    showMessage (msg, color, icon) {
      this.$q.notify({
        message: msg,
        color: color,
        icon: icon
      })
    },
    required
  }
})
</script>

<style>

</style>
