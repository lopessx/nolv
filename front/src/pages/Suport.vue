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
      <div class="row q-py-md justify-center q-gutter-md">
        <q-btn
          label="Problemas com um produto"
          :color="!isProductSupport ? 'black' : 'accent'"
          @click="isProductSupport = true"
        />
        <q-btn
          label="Problemas com o site ou dúvidas"
          :color="isProductSupport ? 'black' : 'accent'"
          @click="isProductSupport = false"
        />
      </div>
      <div class="row justify-start align-center q-pt-md">
        <div class="col-xs-12 col-sm-6 q-pa-md">
          <q-select
            v-if="isProductSupport === true"
            v-model="productInput"
            clearable
            required
            outlined
            :options="productOptions"
            :readonly="loading"
            label="Escolha um produto..."
            input-debounce="0"
            use-input
            @filter="filterFn"
          >
            <template #no-option>
              <q-item>
                <q-item-section class="text-grey">
                  Sem resultados
                </q-item-section>
              </q-item>
            </template>
          </q-select>
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
          @click="(isProductSupport === true) ? sendNewMessage() : sendNewAdminMessage()"
        />
      </div>
    </q-form>
  </q-page>
</template>

<script>
import { api } from 'src/boot/axios'
import { required } from 'src/utils/validations'
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'Suport',
  setup () {
    return {
      clientId: ref(''),
      message: ref(''),
      loading: ref(false),
      productOptions: ref([]),
      allProductOptions: ref([]),
      productInput: ref(''),
      filter: ref(''),
      isProductSupport: ref(true)
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
          const products = response.data.products
          if (products && response.data.success === true) {
            products.forEach(product => {
              this.allProductOptions.push({ label: product.name, value: product.id, store: product.store_id })
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

      if (this.$refs.messageInput.hasError || this.productInput.value === '') {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
        this.loading = false
      } else {
        api.post('/ticket', { message: this.message, clientId: this.clientId, storeId: this.productInput.store, productName: this.productInput.label })
          .then((response) => {
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
    sendNewAdminMessage () {
      this.loading = true

      this.$refs.formMessage.validate()

      if (this.$refs.messageInput.hasError) {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
        this.loading = false
      } else {
        api.post('/ticket/admin', { message: this.message, clientId: this.clientId, storeId: this.productInput.store, productName: this.productInput.label })
          .then((response) => {
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
