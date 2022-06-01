<template>
  <q-page>
    <q-form class="row q-px-md q-py-xl q-gutter-sm">
      <div
        id="col-end"
        class="col-xs-12 col-sm-7"
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
                ref="nameInput"
                v-model="paymentData.name"
                outlined
                label="Nome completo"
                required
                lazy-rules
                :rules="[required]"
                :readonly="loading"
              />
            </div>
            <div class="q-px-sm q-pb-sm">
              <q-input
                ref="emailInput"
                v-model="paymentData.email"
                outlined
                label="Insira seu e-mail"
                type="email"
                required
                lazy-rules
                :rules="[required, emailValidation]"
                :readonly="loading"
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
                :readonly="loading"
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
            <div class="q-pa-md text-grey-7">
              Seu código de acesso irá expirar em 10 minutos.
              Código de acesso enviado ao e-mail <b>{{ paymentData.email }}</b>.<br>
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
                :readonly="loading"
              />
            </div>
          </q-step>
          <q-step
            :name="3"
            :done="step > 3"
            title="Pagamento"
            icon="payments"
          >
            <q-form
              ref="formCheckout"
              @submit.prevent
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
                  :readonly="loading"
                />
              </div>
              <div
                v-if="paymentMethod && paymentMethod.type === 'card'"
                class="row q-pa-sm q-gutter-sm"
              >
                <q-input
                  v-model="paymentData.card.name"
                  class="col-xs-12 col-sm-7"
                  outlined
                  label="Nome do titular do cartão"
                  required
                  lazy-rules
                  :rules="[required]"
                  :readonly="loading"
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
                  :readonly="loading"
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
                  :readonly="loading"
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
                  :readonly="loading"
                />
              </div>
              <div
                v-if="paymentMethod && paymentMethod.type === 'boleto'"
                class="row q-pa-sm q-gutter-sm justify-center"
              >
                <q-input
                  v-model="paymentData.name"
                  class="col-xs-11 col-sm-7"
                  outlined
                  label="Nome completo"
                  required
                  lazy-rules
                  :rules="[required]"
                  :readonly="loading"
                />
                <q-input
                  v-model="paymentData.cpf"
                  class="col-xs-11 col-sm-4"
                  outlined
                  label="CPF"
                  mask="###.###.###-##"
                  required
                  lazy-rules
                  :rules="[required, cpfValidation]"
                  :readonly="loading"
                />
                <q-input
                  v-model="paymentData.email"
                  class="col-11"
                  outlined
                  label="E-mail"
                  type="email"
                  required
                  lazy-rules
                  :rules="[required, emailValidation]"
                  :readonly="loading"
                />
                <q-input
                  v-model="paymentData.slip.zipCode"
                  :disable="loading"
                  debounce="1000"
                  outlined
                  required
                  lazy-rules
                  :rules="[required,cepValidation]"
                  label="CEP"
                  mask="#####-###"
                  class="col-xs-11 col-sm-11"
                  :readonly="loading"
                  @update:model-value="getCep"
                />
                <div class="row col-xs-11 col-sm-12 q-gutter-sm justify-center">
                  <q-select
                    v-model="paymentData.slip.state"
                    :disable="loading"
                    outlined
                    required
                    lazy-rules
                    :rules="[required]"
                    :options="states"
                    label="Estado"
                    class="col-xs-12 col-sm-6"
                    :readonly="loading"
                  />
                  <q-input
                    v-model="paymentData.slip.city"
                    :disable="loading"
                    outlined
                    required
                    lazy-rules
                    :rules="[required]"
                    label="Cidade"
                    class="col-xs-12 col-sm-5"
                    :readonly="loading"
                  />
                  <q-input
                    v-model="paymentData.slip.district"
                    :disable="loading"
                    outlined
                    required
                    lazy-rules
                    :rules="[required]"
                    label="Bairro"
                    class="col-xs-12 col-sm-6"
                    :readonly="loading"
                  />
                  <q-input
                    v-model="paymentData.slip.street"
                    :disable="loading"
                    outlined
                    required
                    lazy-rules
                    :rules="[required]"
                    label="Logradouro"
                    class="col-xs-12 col-sm-5"
                    :readonly="loading"
                  />
                  <q-input
                    v-model="paymentData.slip.number"
                    :disable="loading"
                    outlined
                    required
                    lazy-rules
                    :rules="[required]"
                    label="Número"
                    class="col-xs-12 col-sm-6"
                    :readonly="loading"
                  />
                  <q-input
                    v-model="paymentData.slip.complement"
                    :disable="loading"
                    outlined
                    label="Complemento"
                    class="col-xs-12 col-sm-5"
                    :readonly="loading"
                  />
                </div>
              </div>
            </q-form>
          </q-step>

          <template #navigation>
            <q-stepper-navigation class="row justify-end align-end q-gutter-sm">
              <q-btn
                v-if="step === 1"
                color="primary"
                label="Continuar"
                :loading="loading"
                @click="auth()"
              />
              <q-btn
                v-if="step === 2"
                color="primary"
                label="Continuar"
                :loading="loading"
                @click="login()"
              />
              <q-btn
                v-if="step === 3"
                color="primary"
                label="Confirmar"
                :loading="loading"
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
        <q-card>
          <div class="row q-pa-md text-h6">
            Produtos
          </div>

          <q-separator />

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
                    :disable="loading"
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
        </q-card>
      </div>
    </q-form>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { required, emailValidation, codeValidation, expDate, cvv, phoneValidation, cardValidation, cpfValidation, cepValidation } from 'src/utils/validations'
import { api, request } from 'src/boot/axios'

export default defineComponent({
  name: 'Checkout',
  setup () {
    return {
      count: ref(0),
      loading: ref(false),
      step: ref(1),
      otp: ref(''),
      products: ref([]),
      totalPrice: ref(0),
      paymentMethod: ref(null),
      paymentOptions: ref([]),
      phone: ref(''),
      clientId: ref(''),
      order: ref(null),
      paymentData: ref({
        name: '',
        email: '',
        cpf: '',
        card: {
          name: '',
          number: '',
          cvv: '',
          expDate: ''
        },
        slip: {
          zipCode: '',
          state: '',
          street: '',
          district: '',
          complement: '',
          number: '',
          city: ''
        }
      }),
      states: ref([
        { label: 'Acre', value: 'AC' },
        { label: 'Alagoas', value: 'AL' },
        { label: 'Amapá', value: 'AP' },
        { label: 'Amazonas', value: 'AM' },
        { label: 'Bahia', value: 'BA' },
        { label: 'Ceará', value: 'CE' },
        { label: 'Distrito Federal', value: 'DF' },
        { label: 'Espírito Santo', value: 'ES' },
        { label: 'Goiás', value: 'GO' },
        { label: 'Maranhão', value: 'MA' },
        { label: 'Mato Grosso', value: 'MT' },
        { label: 'Mato Grosso do Sul', value: 'MS' },
        { label: 'Minas Gerais', value: 'MG' },
        { label: 'Pará', value: 'PA' },
        { label: 'Paraíba', value: 'PB' },
        { label: 'Paraná', value: 'PR' },
        { label: 'Pernambuco', value: 'PE' },
        { label: 'Piauí', value: 'PI' },
        { label: 'Rio de Janeiro', value: 'RJ' },
        { label: 'Rio Grande do Norte', value: 'RN' },
        { label: 'Rio Grande do Sul', value: 'RS' },
        { label: 'Rondônia', value: 'RO' },
        { label: 'Roraima', value: 'RR' },
        { label: 'Santa Catarina', value: 'SC' },
        { label: 'São Paulo', value: 'SP' },
        { label: 'Sergipe', value: 'SE' },
        { label: 'Tocantins', value: 'TO' }
      ])
    }
  },

  created () {
    this.getPaymethods()

    const cart = this.$q.localStorage.getItem('cart')
    const client = this.$q.localStorage.getItem('client')

    if (cart) {
      this.products = this.$q.localStorage.getItem('cart')
      this.getTotalPrice()
    }

    if (client) {
      this.clientId = client.id
      this.paymentData.email = client.email
      this.paymentData.name = client.name
      this.paymentData.card.name = client.name
      this.phone = client.phone
      this.step = 3
    }
  },

  methods: {
    auth () {
      this.loading = true
      this.$refs.nameInput.validate()
      this.$refs.emailInput.validate()
      this.$refs.phoneInput.validate()

      if (this.$refs.emailInput.hasError || this.$refs.phoneInput.hasError || this.$refs.nameInput.hasError) {
        this.loading = false

        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.post('/client/auth', { email: this.paymentData.email })
          .then((response) => {
            if (response.data.success === true) {
              this.step = 2
            } else {
              api.post('/client/register', { email: this.paymentData.email, phone: this.phone, name: this.paymentData.name })
                .then((response) => {
                  if (response.data.success === true) {
                    this.step = 2
                  } else {
                    this.showMessage('E-mail inválido', 'negative', 'error')
                  }
                })
                .finally(() => {
                  this.loading = false
                })
            }
          })
          .finally(() => {
            this.loading = false
          })
      }
    },
    login () {
      this.loading = true
      this.$refs.codeInput.validate()

      if (this.count > 2) {
        this.step = 1
        this.email = ''
        this.count = 0
      }

      if (this.$refs.codeInput.hasError) {
        this.count++
        this.loading = false
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.post('/client/login', { email: this.paymentData.email, code: this.otp })
          .then((response) => {
            if (response.data.success === true) {
              const client = response.data.client
              this.$q.localStorage.set('client', response.data.client)
              this.$q.cookies.set('authKey', response.data.key, { expires: 30 })

              this.clientId = client.id
              this.paymentData.email = client.email
              this.paymentData.name = client.name
              this.paymentData.card.name = client.name
              this.phone = client.phone

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
          .catch((error) => {
            this.count++
            this.showMessage('Código inválido', 'negative', 'error')
            console.error('erro identificado ' + error.message + ' code ' + error.code)
          })
          .finally(() => {
            this.loading = false
          })
      }
    },
    placeOrder () {
      this.$refs.formCheckout.validate(false).then(outcome => {
        if (outcome === true) {
          if (this.totalPrice < 1) {
            this.showMessage('Por favor adicione um produto ao carrinho para prosseguir com a compra', 'warning', 'warning')
          } else {
            if (this.order) {
              this.capturePayment()
            } else {
              this.loading = true

              api.post('/order', { total: this.totalPrice, paymethodId: this.paymentMethod.value, clientId: this.clientId, products: this.products })
                .then((response) => {
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
                  this.loading = false
                })
            }
          }
        } else {
          this.showMessage('Preencha todos os campos', 'warning', 'warning')
        }
      })
    },
    async capturePayment () {
      this.$refs.formCheckout.validate(false).then(outcome => {
        if (outcome === true) {
          this.loading = true

          api.post('/payment/capture/' + this.order.id, { paymentData: this.paymentData })
            .then((response) => {
              if (response.data.result === 1 || response.data.result === 2) {
                window.dispatchEvent(new CustomEvent('modify-cart', {
                  detail: {
                    productQtd: 0
                  }
                }))

                this.$q.localStorage.remove('cart')
                this.$router.push('/cliente')
              } else if (response.data.result.url) {
                window.open(response.data.result.url)
                this.$q.localStorage.remove('cart')
                this.$router.push('/cliente')
              } else {
                this.showMessage('Falha ao realizar pagamento', 'negative', 'error')
              }
            })
            .catch((error) => {
              console.error('erro encontrado ' + error.message + ' code ' + error.code)
              this.showMessage('Falha ao realizar pagamento', 'negative', 'error')
            })
            .finally(() => {
              this.loading = false
            })
        } else {
          this.showMessage('Preencha todos os campos', 'warning', 'warning')
        }
      })
    },
    deleteFromCheckout (id) {
      for (let c = 0; c < this.products.length; c++) {
        if (this.products[c].id === id) {
          this.products.splice(c, 1)
        }
      }
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
      this.loading = true

      api.get('/payment/list')
        .then((response) => {
          const paymethods = response.data.paymethods
          paymethods.forEach(element => {
            if (element.active === 1 || element.active === '1') {
              this.paymentOptions.push({ label: element.name, value: element.id, type: element.type })
            }
          })
        })
        .finally(() => {
          this.loading = false
        })
    },
    async getCep (zipCode) {
      request.get('https://viacep.com.br/ws/' + zipCode + '/json')
        .then((response) => {
          if (response.data.uf) {
            this.paymentData.slip.city = response.data.localidade
            this.paymentData.slip.district = response.data.bairro
            this.paymentData.slip.street = response.data.logradouro
            this.paymentData.slip.state = response.data.uf
            return true
          } else {
            return false
          }
        })
        .catch(() => {
          return false
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
    cardValidation,
    cpfValidation,
    cepValidation
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
