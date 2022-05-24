<template>
  <div class="row">
    <div class="row col-12">
      <div class="text-h6 col-6">
        Comentários
      </div>
      <div class="row col-6 justify-end q-px-sm">
        <q-btn
          v-if="hasComment === true"
          icon="edit"
          color="accent"
          round
          size="sm"
          :disable="loading"
          @click="readonly = false"
        />
      </div>
    </div>

    <div
      v-if="enableComment === true"
      class="row col-12 q-py-md"
    >
      <div class="col-12">
        <q-input
          v-model="commentInput"
          type="textarea"
          outlined
          label="Insira seu comentário"
          required
          :readonly="readonly === true || loading === true"
        />
      </div>
      <div class="col-6 q-py-md">
        <q-rating
          v-model="ratingModelClient"
          size="2em"
          :max="5"
          color="accent"
          :readonly="readonly === true || loading === true"
        />
      </div>
      <div class="row col-6 q-py-md justify-end q-gutter-sm">
        <q-btn
          v-if="hasComment"
          color="negative"
          label="Excluir"
          :loading="loading"
          :disable="readonly === true"
          @click="deleteRating()"
        />
        <q-btn
          color="primary"
          label="Enviar"
          :loading="loading"
          :disable="readonly === true"
          @click="hasComment ? updateRating() : sendRating()"
        />
      </div>
    </div>

    <q-list class="col-12">
      <q-skeleton
        v-if="loading"
        type="QInput"
      />
      <div
        v-for="(item, index) in comments"
        v-else
        :key="index"
      >
        <q-item>
          <q-item-section>
            <q-item-label>{{ item.clientName }}</q-item-label>
            <q-item-label>
              <div class="text-subtitle2 text-grey-7">
                {{ item.comment }}
              </div>
            </q-item-label>
          </q-item-section>

          <q-item-section
            side
            top
          >
            <q-item-label caption>
              {{ item.date }}
            </q-item-label>
            <div class="text-orange">
              <q-rating
                v-model="item.rating"
                color="accent"
                readonly
              />
            </div>
          </q-item-section>
        </q-item>

        <q-separator
          spaced
          inset
        />
      </div>
    </q-list>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { api } from 'src/boot/axios'

export default defineComponent({
  name: 'Comments',
  props: {
    clientId: {
      type: String,
      default: ''
    },
    productId: {
      type: String,
      default: ''
    },
    enableComment: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update'],

  setup () {
    return {
      ratingId: ref(''),
      ratingModel: ref(0),
      ratingQtd: ref(0),
      ratingModelClient: ref(0),
      commentInput: ref(''),
      comments: ref([]),
      loading: ref(false),
      readonly: ref(false),
      hasComment: ref(false)
    }
  },

  created () {
    this.loading = true
    this.getRatings()
  },

  methods: {
    async getRatings () {
      const queryRatings = await api.get(`ratings/product/${this.productId}`)
        .then((response) => {
          console.log('response query ratings: ' + JSON.stringify(response.data))
          if (response.data.success === true) {
            this.ratingQtd = response.data.ratings.length
            let ratingAvg = 0
            response.data.ratings.forEach(rating => {
              const newRating = {}
              newRating.clientName = rating.client.name
              newRating.comment = rating.comment
              newRating.rating = rating.rating

              let dateBr = rating.created_at.split('T')
              dateBr = dateBr[0].split('-')
              dateBr = dateBr[2] + '/' + dateBr[1] + '/' + dateBr[0]

              newRating.date = dateBr

              ratingAvg += newRating.rating

              this.comments.push(newRating)
            })

            ratingAvg = parseInt(ratingAvg / this.ratingQtd)
            this.ratingModel = ratingAvg
            console.log('before emit' + this.ratingModel)
            this.$emit('update', { ratingAvg: this.ratingModel, ratingQtd: this.ratingQtd })

            return true
          }
        })
        .catch((error) => {
          console.error('erro encontrado: ' + error.message)

          return false
        })

      if (queryRatings === true && this.clientId !== '') {
        api.get(`/rating/product/${this.productId}/client/${this.clientId}`)
          .then((response) => {
            if (response.data.success === true) {
              const rating = response.data.rating
              this.commentInput = rating.comment
              this.ratingModelClient = rating.rating
              this.ratingId = rating.id

              this.hasComment = true
              this.readonly = true
            }
          })
          .catch((error) => {
            console.error('erro encontrado: ' + error.message)
          })
          .finally(() => {
            this.loading = false
          })
      } else {
        this.loading = false
      }
    },
    sendRating () {
      this.loading = true

      api.post('/rating', { clientId: this.clientId, rating: this.ratingModelClient, comment: this.commentInput, productId: this.productId })
        .then((response) => {
          console.log('response send rating: ' + JSON.stringify(response.data))
          if (response.data.success === true) {
            this.comments = []
            this.commentInput = ''
            this.ratingModelClient = 0
            this.getRatings()
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Envio de comentário falhou', 'negative', 'error')
        })
        .finally(() => {
          this.loading = false
        })
    },
    updateRating () {
      this.loading = true

      api.put(`/rating/${this.ratingId}`, { rating: this.ratingModelClient, comment: this.commentInput })
        .then((response) => {
          if (response.data.success === true) {
            this.comments = []
            this.getRatings()
            this.showMessage('Comentário atualizado com sucesso', 'positive', 'check_circle')
          }
        })
        .catch((error) => {
          console.error('message ' + error.message + ' code ' + error.code)
          this.showMessage('Envio de comentário falhou', 'negative', 'error')
        })
        .finally(() => {
          this.loading = false
        })
    },
    deleteRating () {
      this.$q.dialog({
        title: 'Excluir comentáro',
        message: 'Tem certeza que deseja excluir o comentário? Essa ação é irreversível',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.loading = true
        api.delete(`/rating/${this.ratingId}`)
          .then((response) => {
            if (response.data.success === true) {
              this.hasComment = false
              this.commentInput = ''
              this.ratingModelClient = 0
              this.comments = []

              this.getRatings()
              this.showMessage('Comentário excluído com sucesso', 'positive', 'check_circle')
            } else {
              this.showMessage('Exclusão de comentário falhou', 'negative', 'error')
            }
          })
          .catch((error) => {
            console.error('message ' + error.message + ' code ' + error.code)
            this.showMessage('Exclusão de comentário falhou', 'negative', 'error')
          })
          .finally(() => {
            this.loading = false
          })
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    },
    showMessage (msg, color, icon) {
      this.$q.notify({
        message: msg,
        color: color,
        icon: icon
      })
    }
  }

})
</script>

<style>

</style>
