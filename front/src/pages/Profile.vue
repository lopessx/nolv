<template>
  <q-page>
    <div class="row align-center justify-start q-gutter-xs q-px-md q-pt-lg q-pb-md">
      <q-icon
        name="badge"
        color="accent"
        size="sm"
      />
      <div class="text-subtitle1">
        Perfil
      </div>
    </div>
    <q-separator />
    <div class="row q-px-md q-py-md justify-center">
      <div class="col-11">
        <div class="row justify-start q-pa-md">
          <q-icon
            name="description"
            color="accent"
            size="sm"
          />
          <div class="text-subtitle1">
            Dados pessoais
          </div>
        </div>
        <q-input
          ref="nameInput"
          v-model="name"
          outlined
          label="Nome completo"
          class="q-py-md q-px-sm"
          lazy-rules
          :rules="[required]"
          :loading="loading"
        />
        <q-input
          ref="emailInput"
          v-model="email"
          outlined
          label="E-mail"
          type="email"
          class="q-py-md q-px-sm"
          lazy-rules
          :rules="[required, emailValidation]"
          :loading="loading"
        />
        <q-input
          ref="phoneInput"
          v-model="phone"
          outlined
          label="Número de telefone"
          type="tel"
          mask="(##) ##### - ####"
          class="q-py-md q-px-sm"
          lazy-rules
          :rules="[required, phoneValidation]"
          :loading="loading"
        />
      </div>
      <div class="row col-12 justify-center q-pa-xl q-gutter-sm">
        <q-btn
          color="negative"
          label="Deletar"
          :loading="loading"
          @click="deleteProfile()"
        />
        <q-btn
          color="accent"
          label="Confirmar"
          :loading="loading"
          @click="updateProfile()"
        />
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { api } from 'src/boot/axios'
import { required, emailValidation, phoneValidation } from 'src/utils/validations'

export default defineComponent({
  name: 'ClientArea',
  setup () {
    return {
      email: ref(''),
      balance: ref(0.00),
      name: ref(''),
      phone: ref(''),
      clientId: ref(''),
      loading: ref(false)
    }
  },

  mounted () {
    const client = this.$q.localStorage.getItem('client')
    this.name = client.name
    this.phone = client.phone
    this.email = client.email
    this.clientId = client.id
  },

  methods: {
    updateProfile () {
      this.loading = true
      this.$refs.nameInput.validate()
      this.$refs.emailInput.validate()
      this.$refs.phoneInput.validate()

      if (this.$refs.nameInput.hasError || this.$refs.emailInput.hasError || this.$refs.phoneInput.hasError) {
        this.loading = false
        this.showMessage('Preencha todos os campos', 'warning', 'warning')
      } else {
        api.put(`/client/update/${this.clientId}`, { name: this.name, email: this.email, phone: this.phone })
          .then((response) => {
            if (response.data.success === true) {
              this.$q.localStorage.remove('client')
              this.$q.localStorage.set('client', { name: this.name, phone: this.phone, email: this.email, id: this.clientId })

              window.dispatchEvent(new CustomEvent('client-localstorage-changed', {
                detail: {
                  client: this.$q.localStorage.getItem('client')
                }
              }))

              this.showMessage('Informações atualizadas com sucesso', 'positive', 'check_circle')
            } else {
              this.showMessage('Erro ao atualizar as informações', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Erro ao atualizar as informações', 'negative', 'error')
          })
          .finally(() => {
            this.loading = false
          })
      }
    },
    deleteProfile () {
      this.$q.dialog({
        title: 'Tem certeza que deseja deletar esse perfil?',
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
        this.loading = true

        api.delete(`/client/delete/${this.clientId}`)
          .then((response) => {
            if (response.data.success === true) {
              this.showMessage('Perfil deletado com sucesso', 'positive', 'check_circle')
              this.$q.localStorage.clear()
            } else {
              this.showMessage('Erro ao deletar o perfil', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Erro ao deletar o perfil', 'negative', 'error')
          })
          .finally(() => {
            this.loading = false
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
    phoneValidation
  }
})
</script>

<style>

</style>
