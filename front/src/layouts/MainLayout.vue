<template>
  <q-layout view="lHh Lpr lFf">
    <q-drawer
      v-if="sessionStarted"
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
    <q-header
      elevated
      reveal
    >
      <!-- Toolbar desktop -->
      <q-toolbar class="q-gutter-sm gt-xs">
        <!-- // TODO add logo? -->
        <q-toolbar-title class="col-2">
          <q-btn
            color="primary"
            unelevated
            label="Softnew"
            @click="$router.push('/')"
          />
        </q-toolbar-title>
        <q-input
          v-model="searchText"
          filled
          class="col-7 q-py-xs"
          bg-color="white"
          label="Pesquisar..."
        >
          <template #append>
            <q-icon
              name="search"
              class="cursor-pointer"
              @click="searchProduct(searchText)"
            />
          </template>
        </q-input>

        <q-space />

        <q-btn
          color="primary"
          unelevated
          icon="person"
          label="Entrar"
          @click="$router.push('/login')"
        />
        <q-btn
          color="accent"
          unelevated
          icon="shopping_cart"
          round
          @click="$router.push('/pagamento')"
        />
      </q-toolbar>

      <!-- Toolbar mobile -->
      <q-toolbar class="q-gutter-xs lt-sm">
        <!-- // TODO logo reduzida -->
        <q-btn
          class="col-2"
          color="primary"
          unelevated
          label="SN"
          size="10px"
          @click="$router.push('/')"
        />

        <q-input
          v-model="searchText"
          filled
          class="col-6"
          bg-color="white"
          label="Pesquisar..."
        >
          <template #append>
            <q-icon
              name="search"
              class="cursor-pointer"
              @click="searchProduct(searchText)"
            />
          </template>
        </q-input>

        <q-btn
          class="col-2"
          color="primary"
          unelevated
          icon="person"
          label="Entrar"
          size="10px"
          @click="$router.push('/login')"
        />
        <q-btn
          class="col-1"
          color="accent"
          unelevated
          icon="shopping_cart"
          round
          size="10px"
          @click="$router.push('/login')"
        />
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>

    <footer class="bg-grey-5 q-pa-sm">
      <div class="text-center text-subtitle2">
        © SoftNew - 2021
      </div>
    </footer>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'MainLayout',

  setup () {
    return {
      searchText: ref(''),
      drawer: ref(false),
      miniState: ref(true),
      sessionStarted: ref(false)
    }
  },
  methods: {
    searchProduct (searchText) {
      // TODO generate search method and return
      console.log('procurar produto: ' + JSON.stringify(searchText))
    }
  }
})
</script>
