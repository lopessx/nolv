<template>
  <q-page>
    <q-form class="row q-py-xl justify-center align center">
      <div class="col-xs-12 col-sm-8">
        <q-stepper
          v-model="step"
          animated
          :contracted="$q.screen.lt.sm"
        >
          <q-step
            :name="1"
            :done="step > 1"
            title="Login"
            icon="person"
          >
            <div class="text-h4 q-pa-md text-grey-7">
              Login
            </div>
            <q-separator />
            <div class="q-px-sm q-py-lg">
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
              Código de acesso enviado ao e-mail <b>{{ email }}</b>
            </div>
            <div class="q-px-sm q-py-lg">
              <q-input
                ref="codeInput"
                v-model="otp"
                outlined
                label="Insira o código de acesso"
                type="tel"
                mask="######"
                lazy-rules
                :rules="[codeValidation, required]"
                required
              />
            </div>
          </q-step>

          <template #navigation>
            <q-stepper-navigation>
              <div class="row justify-end q-gutter-xs">
                <!-- Auth request -->
                <q-btn
                  v-if="step === 1"
                  color="accent"
                  label="Cadastrar"
                  @click="$router.push('/registro')"
                />
                <q-btn
                  v-if="step === 1"
                  color="primary"
                  label="Continuar"
                  @click="auth()"
                />
                <!-- Login validation -->
                <q-btn
                  v-if="step > 1"
                  flat
                  color="primary"
                  label="Voltar"
                  class="q-ml-sm"
                  @click="step--"
                />
                <q-btn
                  v-if="step === 2"
                  color="primary"
                  label="Confirmar"
                  @click="login()"
                />
              </div>
            </q-stepper-navigation>
          </template>
        </q-stepper>
      </div>
    </q-form>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { api } from 'src/boot/axios'
import { required, emailValidation, phoneValidation, codeValidation } from 'src/utils/validations'

export default defineComponent({
  name: 'Login',
  setup () {
    return {
      step: ref(1),
      email: ref(''),
      otp: ref('')
    }
  },

  mounted () {
    console.log('carregado')
  },

  methods: {
    auth () {
      this.$refs.emailInput.validate()

      if (this.$refs.emailInput.hasError) {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.post('client/auth', { email: this.email })
          .then((response) => {
            console.log('solicitação de autenticação feita ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.step = 2
            } else {
              this.showMessage('E-mail inválido ou não cadastrado', 'negative', 'error')
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
              this.$q.sessionStorage.set('client', response.data.client)
              this.$q.sessionStorage.set('authKey', response.data.key)

              window.dispatchEvent(new CustomEvent('client-localstorage-changed', {
                detail: {
                  client: this.$q.sessionStorage.getItem('client')
                }
              }))

              this.$router.push('/cliente')
            } else {
              this.showMessage('Código inválido', 'negative', 'error')
            }
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
    required,
    emailValidation,
    phoneValidation,
    codeValidation
  }
})
</script>

<style>

</style>
