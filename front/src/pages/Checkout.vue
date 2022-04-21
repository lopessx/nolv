<template>
  <q-page>
    <q-form class="row q-px-md q-py-xl">
      <div
        id="col-end"
        class="col-xs-12 col-sm-8"
      >
        <q-stepper
          v-model="step"
          animated
          :contracted="$q.screen.lt.sm"
        >
          <q-step
            :name="1"
            :done="step > 1"
            title="Cadastro"
            icon="person"
          >
            <div class="text-h4 q-pa-md text-grey-7">
              Cadastro
            </div>
            <q-separator />
            <div class="q-px-sm q-pb-sm q-pt-lg">
              <q-input
                ref="emailInput"
                v-model="email"
                outlined
                label="Insira seu e-mail"
                type="email"
                required
                lazy-rules
                :rules="[required, emailValidation]"
              />
            </div>
            <div class="q-px-sm q-pt-sm q-pb-lg">
              <q-input
                ref="phoneInput"
                v-model="phone"
                outlined
                label="Insira seu número de celular"
                type="tel"
                mask="(##) ##### - ####"
                required
                lazy-rules
                :rules="[required, phoneValidation]"
              />
            </div>
          </q-step>
          <q-step
            :name="2"
            :done="step > 2"
            title="Validação"
            icon="assignment_ind"
          >
            <div class="text-h4 q-pa-md text-grey-7">
              Código de acesso
            </div>
            <q-separator />
            <div class="q-px-sm q-py-lg">
              <q-input
                ref="codeInput"
                v-model="otp"
                outlined
                label="Insira o código de acesso"
                type="tel"
                mask="######"
                required
                lazy-rules
                :rules="[required, codeValidation]"
              />
            </div>
          </q-step>
          <q-step
            :name="3"
            :done="step > 3"
            title="Pagamento"
            icon="payments"
          >
            <div class="text-h4 q-pa-md text-grey-7">
              Pagamento
            </div>
            <q-separator />
            <div class="q-px-sm q-py-lg">
              <q-select
                v-model="paymentMethod"
                outlined
                :options="paymentOptions"
                label="Escolha uma forma de pagamento"
                required
              />
            </div>
            <div
              v-if="paymentMethod && paymentMethod.type === 'card'"
              class="row q-pa-sm q-gutter-md"
            >
              <q-input
                v-model="paymentData.card.name"
                class="col-xs-12 col-sm-7"
                outlined
                label="Nome do titular do cartão"
                required
                lazy-rules
                :rules="[required]"
              />
              <q-input
                v-model="paymentData.card.expDate"
                class="col-xs-12 col-sm-4"
                outlined
                label="Data de expiração"
                mask="##/##"
                required
                lazy-rules
                :rules="[required, expDate]"
              />
              <q-input
                v-model="paymentData.card.number"
                class="col-xs-12 col-sm-7"
                outlined
                label="Número do cartão"
                mask="#### #### #### #### ####"
                required
                lazy-rules
                :rules="[required]"
              />
              <q-input
                v-model="paymentData.card.cvv"
                class="col-xs-12 col-sm-4"
                outlined
                label="CVV"
                mask="####"
                required
                lazy-rules
                :rules="[required, cvv]"
              />
            </div>
            <div
              v-if="paymentMethod && (paymentMethod.type === 'pix' || paymentMethod.type === 'boleto')"
              class="row q-pa-sm q-gutter-md justify-center"
            >
              <q-input
                v-model="paymentData.name"
                class="col-xs-12 col-sm-7"
                outlined
                label="Nome completo"
                required
              />
              <q-input
                v-model="paymentData.cpf"
                class="col-xs-12 col-sm-4"
                outlined
                label="CPF"
                mask="###.###.###-##"
                required
              />
              <q-input
                v-model="paymentData.email"
                class="col-12"
                outlined
                label="E-mail"
                type="email"
                required
              />
            </div>
          </q-step>

          <template #navigation>
            <q-stepper-navigation class="row justify-end align-end q-gutter-sm">
              <q-btn
                v-if="step === 1"
                color="primary"
                label="Continuar"
                @click="auth()"
              />
              <q-btn
                v-if="step === 2"
                color="primary"
                label="Continuar"
                @click="login()"
              />
              <q-btn
                v-if="step === 3"
                color="primary"
                label="Confirmar"
                @click="placeOrder()"
              />
            </q-stepper-navigation>
          </template>
        </q-stepper>
      </div>
      <div
        id="col-start"
        class="col-xs-12 col-sm-4"
      >
        <q-list>
          <div
            v-for="product in products"
            :key="product.id"
          >
            <q-item>
              <q-item-section>
                <q-item-label>{{ product.name }}</q-item-label>
              </q-item-section>

              <q-item-section side>
                <q-item-label class="text-weight-bold">
                  {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(product.price) }}
                  <q-btn
                    round
                    color="red"
                    icon="remove_circle"
                    size="xs"
                    @click="deleteFromCheckout(product.id)"
                  />
                </q-item-label>
              </q-item-section>
            </q-item>

            <q-separator
              spaced
              inset
            />
          </div>

          <q-item>
            <q-item-section class="text-weight-bold">
              <q-item-label>Preço final</q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-item-label class="text-weight-bold q-pr-lg">
                {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(totalPrice) }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-separator
            spaced
            inset
          />
        </q-list>
      </div>
    </q-form>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { required, emailValidation, codeValidation, expDate, cvv, phoneValidation, cardValidation } from 'src/utils/validations'
import { api } from 'src/boot/axios'

export default defineComponent({
  name: 'Checkout',
  setup () {
    return {
      step: ref(3),
      email: ref(''),
      otp: ref(''),
      products: ref([]),
      totalPrice: ref(0),
      paymentMethod: ref(null),
      paymentOptions: ref([]),
      phone: ref(''),
      paymentData: ref({
        name: '',
        email: '',
        cpf: '',
        card: {
          name: '',
          number: '',
          cvv: '',
          expDate: ''
        }
      }),
      order: ref(null)
    }
  },

  created () {
    const cart = this.$q.localStorage.getItem('cart')

    if (cart) {
      this.products = this.$q.localStorage.getItem('cart')
      this.getTotalPrice()
    }
  },

  mounted () {
    console.log('carregado')
    this.getPaymethods()
  },

  methods: {
    auth () {
      this.$refs.emailInput.validate()
      this.$refs.phoneInput.validate()

      if (this.$refs.emailInput.hasError || this.$refs.phoneInput.hasError) {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.post('client/auth', { email: this.email })
          .then((response) => {
            console.log('solicitação de autenticação feita ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.step = 2
            } else {
              api.post('client/register', { email: this.email, phone: this.phone })
                .then((response) => {
                  if (response.data.success === true) {
                    this.step = 2
                  } else {
                    this.showMessage('E-mail inválido', 'negative', 'error')
                  }
                })
            }
          })
      }
    },
    login () {
      this.$refs.codeInput.validate()

      if (this.$refs.codeInput.hasError) {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.post('client/login', { email: this.email, code: this.otp })
          .then((response) => {
            console.log('login efetuado ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.$q.localStorage.set('client', response.data.client)
              this.$q.localStorage.set('authKey', response.data.key)

              window.dispatchEvent(new CustomEvent('client-localstorage-changed', {
                detail: {
                  client: this.$q.localStorage.getItem('client')
                }
              }))

              this.step = 3
            } else {
              this.showMessage('Código inválido', 'negative', 'error')
            }
          })
      }
    },
    placeOrder () {
      const client = this.$q.localStorage.getItem('client')

      api.post('payment/order', { total: this.totalPrice, paymethodId: this.paymentMethod.value, clientId: client.id, products: this.products })
        .then((response) => {
          console.log('novo pedido ' + JSON.stringify(response.data))
          if (response.data.success === true) {
            this.order = response.data.order
            this.capturePayment()
          } else {
            this.showMessage('Falha ao realizar novo pedido', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('erro mensagem: ' + error.message)
          this.showMessage('Falha ao realizar novo pedido', 'negative', 'error')
        })
    },
    async capturePayment () {
      api.post('payment/capture/' + this.order.id, { paymentData: this.paymentData })
        .then((response) => {
          if (response.data.success === true) {
            console.log('caputura dados: ' + response.data)
            this.$router.push('/cliente')
          } else {
            this.showMessage('Falha ao realizar pagamento', 'negative', 'error')
          }
        })
    },
    deleteFromCheckout (id) {
      console.log('produto a ser deletado: ' + id)
      for (let c = 0; c < this.products.length; c++) {
        console.log('repetição produto: ' + this.products[c].id + ' id deletado: ' + id)
        if (this.products[c].id === id) {
          this.products.splice(c, 1)
        }
      }
      console.log('produtos ' + JSON.stringify(this.products))
      this.$q.localStorage.set('cart', this.products)

      window.dispatchEvent(new CustomEvent('modify-cart', {
        detail: {
          productQtd: this.products.length
        }
      }))

      this.getTotalPrice()
    },
    getTotalPrice () {
      this.totalPrice = 0
      for (let c = 0; c < this.products.length; c++) {
        this.totalPrice += this.products[c].price
      }
    },
    async getPaymethods () {
      api.get('payment/list')
        .then((response) => {
          console.log('payment methods ' + JSON.stringify(response.data.paymethods))
          const paymethods = response.data.paymethods
          paymethods.forEach(element => {
            if (element.active === 1) {
              this.paymentOptions.push({ label: element.name, value: element.id, type: element.type })
            }
          })
        })
    },
    showMessage (msg, color, icon) {
      this.$q.notify({
        message: msg,
        color: color,
        icon: icon
      })
    },
    required,
    emailValidation,
    codeValidation,
    expDate,
    phoneValidation,
    cvv,
    cardValidation
  }
})
</script>

<style>
@media screen and (max-width: 599px) {
  #col-start{
    order: 1;
  }
  #col-end {
    order: 2;
  }
}
</style>
