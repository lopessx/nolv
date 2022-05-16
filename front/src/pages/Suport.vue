<template>
  <q-page>
    <div class="row justify-start q-pa-md">
      <q-icon
        size="sm"
        name="contact_support"
        color="accent"
      />
      <div class="text-subtitle1">
        Suporte
      </div>
    </div>
    <q-separator />
    <q-form ref="formMessage">
      <div class="row justify-start align-center q-pt-md">
        <div class="col-6 q-pa-md">
          <q-select
            v-model="storeId"
            required
            outlined
            map-options
            emit-value
            :options="productOptions"
            :readonly="loading"
            label="Enviar para..."
            input-debounce="0"
            use-input
            @filter="filterFn"
          />
        </div>
      </div>
      <div class="row justify-center align-center">
        <div class="col-12 q-pa-md">
          <q-input
            ref="messageInput"
            v-model="message"
            filled
            type="textarea"
            label="Insira aqui sua mensagem..."
            required
            lazy-rules
            :rules="[required]"
            :readonly="loading"
          />
        </div>
        <q-btn
          :loading="loading"
          color="accent"
          label="Enviar"
          @click="sendNewMessage"
        />
      </div>
    </q-form>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { required } from 'src/utils/validations'
import { defineComponent, ref } from 'vue'

const stringOptions = [
  'Google', 'Facebook', 'Twitter', 'Apple', 'Oracle'
]

export default defineComponent({
  name: 'Suport',
  setup () {
    const options = ref(stringOptions)
    return {
      clientId: ref(''),
      model: ref(null),
      message: ref(''),
      loading: ref(false),
      options,
      productOptions: ref([]),
      allProductOptions: ref([]),
      storeId: ref(''),
      storeName: ref('Minha loja'),
      email: ref('anymail@gmas.com'),
      products: ref([{ id: 0, name: 'Abacaxi', price: 21.00 }, { id: 1, name: 'Banana', price: 10.00 }, { id: 2, name: 'Batata', price: 34.23 }]),
      balance: ref(19.23),
      drawer: ref(false),
      miniState: ref(true),
      username: ref('João'),
      filter: ref(''),
      productsPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0]),
      lorem: ref('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')
    }
  },

  created () {
    const client = this.$q.localStorage.getItem('client')

    if (client) {
      this.clientId = client.id
    }

    this.getClientProducts()
  },

  methods: {
    filterFn (val, update, abort) {
      if (val.length < 2) {
        update(() => { this.productOptions = this.allProductOptions })
        return
      }
      update(() => {
        const needle = val.toLowerCase()
        this.productOptions = this.allProductOptions.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
      })
    },
    getClientProducts () {
      this.loading = true

      api.get(`/products/client/${this.clientId}`)
        .then((response) => {
          console.log('reposta produtos ' + JSON.stringify(response.data))
          const products = response.data.products
          if (products && response.data.success === true) {
            products.forEach(product => {
              this.allProductOptions.push({ label: product.name, value: product.store_id })
            })
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Erro ao consultar produtos', 'negative', 'error')
        })
        .finally(() => {
          this.loading = false
        })
    },
    sendNewMessage () {
      this.loading = true

      this.$refs.formMessage.validate()

      if (this.$refs.messageInput.hasError || this.storeId === '') {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
        this.loading = false
      } else {
        api.post('/ticket', { message: this.message, clientId: this.clientId, storeId: this.storeId })
          .then((response) => {
            console.log('resposta: ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.showMessage('Mensagem enviada com sucesso', 'positive', 'check_circle')
              this.$router.push('/cliente')
            } else {
              this.showMessage('Erro ao enviar mensagem', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Erro ao enviar mensagem', 'negative', 'error')
          })
          .finally(() => {
            this.loading = false
          })
      }
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
