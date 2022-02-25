<template>
  <div class="col-12 text-h6">
    Comentários
  </div>
  <div class="q-py-md col-12">
    <q-input
      v-model="comment"
      type="textarea"
      outlined
      label="Insira seu comentário..."
    >
      <template #append>
        <q-rating
          v-model="newRating"
          color="accent"
        />
      </template>
    </q-input>
  </div>
  <div class="col-auto justify-end q-pb-lg">
    <q-btn
      color="primary"
      label="enviar"
      @click="sendComment"
    />
  </div>
  <q-list class="col-12">
    <q-infinite-scroll
      :offset="250"
      @load="onLoad"
    >
      <div
        v-for="(item, index) in ratings"
        :key="index"
      >
        <q-item>
          <q-item-section>
            <q-item-label>{{ item.client.name }}</q-item-label>
            <q-item-label caption>
              {{ item.comment }}
            </q-item-label>
          </q-item-section>

          <q-item-section
            side
            top
          >
            <q-item-label caption>
              {{ toBrazilianDate(item.created_at) }}
            </q-item-label>
            <div class="text-orange">
              <q-rating
                :model-value="item.rating"
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
      <template #loading>
        <div class="row justify-center q-my-md">
          <q-spinner-dots
            color="primary"
            size="40px"
          />
        </div>
      </template>
    </q-infinite-scroll>
  </q-list>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { toBrazilianDate } from 'src/utils/functions'
// import api from 'src/boot/axios'

export default defineComponent({
  name: 'Comments',
  props: {
    ratings: {
      type: Array,
      default: Array
    },
    productId: {
      type: String,
      default: '0'
    }
  },
  emits: ['save'],

  setup () {
    return {
      comment: ref(''),
      newRating: ref(0)
    }
  },
  methods: {
    onLoad (index, done) {
      done()
      // TODO maybe implement pagination?
      /* console.log('index value: ' + index)
      if (index > 1) {
        setTimeout(() => {
          this.items.push({}, {}, {}, {})
          done()
        }, 2000)
      } else {
        setTimeout(() => {
          this.items.push({}, {}, {}, {})
          done()
        }, 2000)
      } */
    },
    toBrazilianDate,
    async sendComment () {
      console.log('botão clicado')
      // TODO add client id for logged users
      this.$emit('save', { product_id: this.productId, rating: this.newRating, comment: this.comment, client_id: 1 })
    }
  }

})
</script>

<style>

</style>