<template>
  <q-page>
    <div class="row align-center justify-start q-gutter-xs q-px-md q-pt-lg q-pb-md">
      <q-icon
        name="person"
        color="accent"
        size="sm"
      />
      <div class="text-subtitle1">
        Perfil
      </div>
    </div>
    <q-separator />
    <div class="row q-px-md q-py-md">
      <div class="col-6">
        <div class="row justify-start q-pa-md">
          <q-icon
            name="person"
            color="accent"
            size="sm"
          />
          <div class="text-subtitle1">
            Dados pessoais
          </div>
        </div>
        <q-input
          v-model="name"
          outlined
          label="Nome completo"
          class="q-py-md q-px-sm"
        />
        <q-input
          v-model="email"
          outlined
          label="E-mail"
          type="email"
          class="q-py-md q-px-sm"
        />
        <q-input
          v-model="phone"
          outlined
          label="Número de telefone"
          type="tel"
          mask="(##) #####-####"
          class="q-py-md q-px-sm"
        />
      </div>
      <q-separator vertical />
      <div class="col-5">
        <div class="row justify-start q-pa-md">
          <q-icon
            name="credit_card"
            color="accent"
            size="sm"
          />
          <div class="text-subtitle1">
            Cartões cadastrados
          </div>
        </div>
        <q-list bordered>
          <div v-if="cards.length < 1">
            <q-item
              v-ripple
              clickable
              class="q-pa-lg"
            >
              <q-item-section>Nenhum cartão cadastrado.</q-item-section>
            </q-item>
          </div>
          <div
            v-for="card in cards"
            :key="card.id"
          >
            <q-item
              v-ripple
              clickable
              class="q-pa-lg"
            >
              <q-item-section>{{ card.number }} - {{ card.brand }}</q-item-section>
              <q-item-section avatar>
                <q-icon
                  color="accent"
                  name="delete"
                  @click="deleteCard(card.id)"
                />
              </q-item-section>
            </q-item>
            <q-separator inset />
          </div>
        </q-list>
      </div>
      <div class="row col-12 justify-center q-pa-xl q-gutter-sm">
        <q-btn
          color="negative"
          label="Deletar"
          @click="deleteProfile()"
        />
        <q-btn
          color="accent"
          label="Confirmar"
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
      cards: ref([])
    }
  },

  mounted () {
    console.log('carregado')
    const client = this.$q.localStorage.getItem('client')
    this.name = client.name
    this.phone = client.phone
    this.email = client.email
    this.clientId = client.id
  },

  methods: {
    deleteCard (val) {
      console.log('cartão deletado ' + val)
    },
    updateProfile () {
      console.log('alterações salvas')
      api.put(`/client/update/${this.clientId}`, { name: this.name, email: this.email, phone: this.phone })
        .then((response) => {
          if (response.data.success === true) {
            this.showMessage('Informações atualizadas com sucesso', 'positive', 'check_circle')
          } else {
            this.showMessage('Erro ao atualizar as informações', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Erro ao atualizar as informações', 'negative', 'error')
        })
    },
    deleteProfile () {
      console.log('deletar perfil')
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
