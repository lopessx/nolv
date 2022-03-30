<template>
  <q-page>
    <div class="row q-px-md q-py-xl align-center justify-center">
      <div class="col-xs-12 col-sm-8">
        <q-stepper
          v-model="step"
          animated
          :contracted="$q.screen.lt.sm"
        >
          <!-- // TODO adicionar link redirecionando para cadastro, utilizar página de checkout de novos clientes -->
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
                v-model="email"
                outlined
                label="Insira seu e-mail"
                type="email"
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
            <div class="text-h4 q-pa-md text-grey-7">
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

          <template #navigation>
            <q-stepper-navigation>
              <div class="row justify-end q-gutter-xs">
                <q-btn
                  v-if="step > 1"
                  flat
                  color="primary"
                  label="Voltar"
                  class="q-ml-sm"
                  @click="step--"
                />
                <q-btn
                  v-if="step === 1"
                  color="accent"
                  label="Cadastrar"
                  @click="$router.push('/registro')"
                />
                <q-btn
                  color="primary"
                  :label="step === 2 ? 'Confirmar' : 'Continuar'"
                  @click="loginCheck()"
                />
              </div>
            </q-stepper-navigation>
          </template>
        </q-stepper>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'

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
    loginCheck () {
      this.step++
      console.log('login foi feito ' + this.otp)
    }
  }
})
</script>

<style>

</style>
