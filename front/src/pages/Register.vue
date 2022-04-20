<template>
  <q-page>
    <q-form class="row q-py-xl justify-center align center">
      <q-stepper
        v-model="step"
        class="col-xs-12 col-sm-8"
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
              v-model="name"
              outlined
              label="Nome completo"
              required
              lazy-rules
              :rules="[required]"
            />
          </div>
          <div class="q-px-sm q-pb-sm q-pt-sm">
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
              hint="Código de 6 dígitos"
              lazy-rules
              :rules="[codeValidation, required]"
              required
            />
          </div>
        </q-step>

        <template #navigation>
          <q-stepper-navigation class="row justify-end align-end q-gutter-sm">
            <q-btn
              v-if="step > 1"
              color="primary"
              label="Confirmar"
              @click="login()"
            />
            <q-btn
              v-if="step === 1"
              color="primary"
              label="Cadastrar"
              @click="register()"
            />
          </q-stepper-navigation>
        </template>
      </q-stepper>
    </q-form>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
// eslint-disable-next-line no-unused-vars
import { api } from 'src/boot/axios'
import { required, emailValidation, phoneValidation, codeValidation } from 'src/utils/validations'

export default defineComponent({
  name: 'Register',
  setup () {
    return {
      step: ref(1),
      email: ref(''),
      phone: ref(''),
      name: ref(''),
      otp: ref('')
    }
  },
  methods: {
    register () {
      console.log('api call to register')
      this.$refs.nameInput.validate()
      this.$refs.emailInput.validate()
      this.$refs.phoneInput.validate()

      if (this.$refs.nameInput.hasError || this.$refs.emailInput.hasError || this.$refs.phoneInput.hasError) {
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.post('/client/register', { email: this.email, name: this.name, phone: this.phone })
          .then((response) => {
            if (response.data.success === true) {
              console.log('resposta do registro ' + JSON.stringify(response.data))
              this.step = 2
            } else {
              this.showMessage('Erro ao registrar novo cliente', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.log('erro ' + error.message)
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
