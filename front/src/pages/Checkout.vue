<template>
  <q-page>
    <div class="row q-px-md q-py-xl">
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
            <div
              class="text-h4 q-pa-md"
              style="color: #838383;"
            >
              Cadastro
            </div>
            <q-separator />
            <div class="q-px-sm q-pb-sm q-pt-lg">
              <q-input
                v-model="email"
                outlined
                label="Insira seu e-mail"
                type="email"
                required
              />
            </div>
            <div class="q-px-sm q-pt-sm q-pb-lg">
              <q-input
                v-model="phone"
                outlined
                label="Insira seu número de celular"
                type="tel"
                mask="(##) ##### - ####"
                required
              />
            </div>
          </q-step>
          <q-step
            :name="2"
            :done="step > 2"
            title="Validação"
            icon="assignment_ind"
          >
            <div
              class="text-h4 q-pa-md"
              style="color: #838383;"
            >
              Código de acesso
            </div>
            <q-separator />
            <div class="q-px-sm q-py-lg">
              <q-input
                v-model="otp"
                outlined
                label="Insira o código de acesso"
                type="tel"
                mask="######"
                required
              />
            </div>
          </q-step>
          <q-step
            :name="3"
            :done="step > 3"
            title="Pagamento"
            icon="payments"
          >
            <div
              class="text-h4 q-pa-md"
              style="color: #838383;"
            >
              Pagamento
            </div>
            <q-separator />
            <div class="q-px-sm q-py-lg">
              <q-select
                v-model="paymentForm"
                outlined
                :options="paymentOptions"
                label="Escolha uma forma de pagamento"
                required
              />
            </div>
            <div
              v-if="paymentForm === 'cartão de crédito'"
              class="row q-pa-sm q-gutter-md"
            >
              <q-input
                v-model="card.name"
                class="col-xs-12 col-sm-7"
                outlined
                label="Nome do titular do cartão"
                required
              />
              <q-input
                v-model="card.expDate"
                class="col-xs-12 col-sm-4"
                outlined
                label="Data de expiração"
                mask="## / ##"
                required
              />
              <q-input
                v-model="card.number"
                class="col-xs-12 col-sm-7"
                outlined
                label="Número do cartão"
                mask="#### #### #### #### ####"
                required
              />
              <q-input
                v-model="card.cvv"
                class="col-xs-12 col-sm-4"
                outlined
                label="CVV"
                mask="####"
                required
              />
            </div>
            <div
              v-if="paymentForm === 'boleto' || paymentForm === 'pix'"
              class="row q-pa-sm q-gutter-md"
            >
              <q-input
                v-model="paymentData.name"
                class="col-xs-12 col-sm-5"
                outlined
                label="Nome completo"
                required
              />
              <q-input
                v-model="paymentData.cpf"
                class="col-xs-12 col-sm-5"
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
                v-if="step > 1"
                flat
                color="primary"
                label="Voltar"
                @click="step--"
              />
              <q-btn
                color="primary"
                :label="step === 3 ? 'Confirmar' : 'Continuar'"
                @click="loginCheck()"
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
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'Checkout',
  setup () {
    return {
      step: ref(1),
      email: ref(''),
      otp: ref(''),
      products: ref([{ id: 0, name: 'Abacaxi', price: 21.00 }, { id: 1, name: 'Banana', price: 10.00 }, { id: 2, name: 'Batata', price: 34.23 }]),
      totalPrice: ref(65.23),
      paymentForm: ref(''),
      paymentOptions: ref(['cartão de crédito', 'boleto', 'pix']),
      phone: ref(''),
      card: ref({
        name: '',
        number: '',
        cvv: '',
        expDate: ''
      }),
      paymentData: ref({
        name: '',
        email: '',
        cpf: ''
      })
    }
  },

  mounted () {
    console.log('carregado')
  },

  methods: {
    loginCheck () {
      this.step++
      console.log('login foi feito ' + this.otp)
    },
    deleteFromCheckout (id) {
      console.log('produto a ser deletado: ' + id)
    }
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
