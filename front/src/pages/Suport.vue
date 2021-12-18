<template>
  <q-page>
    <q-drawer
      v-model="drawer"
      show-if-above

      :mini="miniState"
      mini-to-overlay
      :width="200"
      :breakpoint="500"

      bordered
      class="bg-primary text-secondary"
      @mouseover="miniState = false"
      @mouseout="miniState = true"
    >
      <q-scroll-area class="fit">
        <q-list padding>
          <q-item
            v-ripple
            clickable
          >
            <q-item-section avatar>
              <q-icon
                name="home"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Início
            </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            v-ripple
            clickable
          >
            <q-item-section avatar>
              <q-icon
                name="person"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Perfil
            </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            v-ripple
            clickable
          >
            <q-item-section avatar>
              <q-icon
                name="store"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Minha loja
            </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            v-ripple
            clickable
          >
            <q-item-section avatar>
              <q-icon
                name="contact_support"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Suporte
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <div class="row justify-start q-pa-md">
      <q-icon
        size="sm"
        name="contact_support"
        color="accent"
      />
      <div class="text-subtitle1">
        Suporte
      </div>
    </div>
    <q-separator />
    <div class="row justify-start align-center q-pt-md">
      <div class="col-6 q-pa-md">
        <q-select
          v-model="model"
          outlined
          :options="options"
          label="Enviar para..."
          input-debounce="0"
          use-input
          @filter="filterFn"
        />
      </div>
    </div>
    <div class="row justify-center align-center">
      <div class="col-12 q-pa-md">
        <q-input
          filled
          type="textarea"
          label="Insira aqui sua mensagem..."
        />
      </div>
      <q-btn
        color="accent"
        label="Enviar"
      />
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'

const stringOptions = [
  'Google', 'Facebook', 'Twitter', 'Apple', 'Oracle'
]

export default defineComponent({
  name: 'Suport',
  setup () {
    const options = ref(stringOptions)
    return {
      model: ref(null),
      options,

      filterFn (val, update, abort) {
        update(() => {
          const needle = val.toLowerCase()
          options.value = stringOptions.filter(v => v.toLowerCase().indexOf(needle) > -1)
        })
      },
      storeName: ref('Minha loja'),
      email: ref('anymail@gmas.com'),
      products: ref([{ id: 0, name: 'Abacaxi', price: 21.00 }, { id: 1, name: 'Banana', price: 10.00 }, { id: 2, name: 'Batata', price: 34.23 }]),
      balance: ref(19.23),
      drawer: ref(false),
      miniState: ref(true),
      username: ref('João'),
      filter: ref(''),
      productsPerPage: ref([6, 9, 15, 21, 27, 30, 42, 0]),
      lorem: ref('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')
    }
  }
})
</script>

<style>

</style>
