<template>
  <q-page>
    <q-form
      class="row q-py-xl q-px-md justify-center align center"
      @submit.prevent
    >
      <div class="col-xs-12 col-sm-8">
        <q-stepper
          v-model="step"
          animated
          :contracted="$q.screen.lt.sm"
        >
          <!-- Email verification -->
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
                :readonly="loading"
                :rules="[required, emailValidation]"
              />
            </div>
          </q-step>
          <!-- Code authentication -->
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
              Código de acesso enviado ao e-mail <b>{{ email }}</b>.<br>
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
                required
                :disable="loading"
                :rules="[codeValidation, required]"
              />
            </div>
          </q-step>

          <!-- Buttons -->
          <template #navigation>
            <q-stepper-navigation>
              <div class="row justify-end q-gutter-xs">
                <!-- Auth request -->
                <q-btn
                  v-if="step === 1"
                  color="accent"
                  label="Cadastrar"
                  :disable="loading"
                  @click="$router.push('/registro')"
                />
                <q-btn
                  v-if="step === 1"
                  color="primary"
                  label="Continuar"
                  :loading="loading"
                  @click="auth()"
                />
                <!-- Login validation -->
                <q-btn
                  v-if="step > 1"
                  flat
                  color="primary"
                  label="Voltar"
                  class="q-ml-sm"
                  :disable="loading"
                  @click="step--"
                />
                <q-btn
                  v-if="step === 2"
                  color="primary"
                  label="Confirmar"
                  :loading="loading"
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
import { required, emailValidation, phoneValidation, codeValidation } from 'src/utils/validations'
import { api } from 'src/boot/axios'

export default defineComponent({
  name: 'Login',
  setup () {
    return {
      step: ref(1),
      email: ref(''),
      otp: ref(''),
      loading: ref(false),
      count: ref(0)
    }
  },

  mounted () {
    const client = this.$q.localStorage.getItem('client')

    if (client) {
      this.$router.push('/cliente')
    }
  },

  methods: {
    auth () {
      this.loading = true
      this.$refs.emailInput.validate()

      if (this.$refs.emailInput.hasError) {
        this.loading = false
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.post('/client/auth', { email: this.email })
          .then((response) => {
            console.log('solicitação de autenticação feita ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.step = 2
            } else {
              this.showMessage('E-mail inválido ou não cadastrado', 'negative', 'error')
            }
          })
          .catch((error) => {
            this.showMessage('E-mail inválido ou não cadastrado', 'negative', 'error')
            console.error('erro identificado ' + error.message + ' code ' + error.code)
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
        api.post('/client/login', { email: this.email, code: this.otp })
          .then((response) => {
            console.log('login efetuado ' + JSON.stringify(response.data))
            if (response.data.success === true) {
              this.$q.localStorage.set('client', response.data.client)
              this.$q.cookies.set('authKey', response.data.key)

              console.log('cookie ' + this.$q.cookies.get('authKey'))

              window.dispatchEvent(new CustomEvent('client-localstorage-changed', {
                detail: {
                  client: this.$q.localStorage.getItem('client')
                }
              }))

              this.$router.push('/cliente')
            } else {
              this.count++
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
