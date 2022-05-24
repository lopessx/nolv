<template>
  <q-layout view="lHh Lpr lFf">
    <q-drawer
      v-if="sessionStarted"
      v-model="drawer"
      show-if-above

      :mini="miniState"
      mini-to-overlay
      :width="200"
      :breakpoint="200"

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
            class="text-secondary"
            to="/cliente"
          >
            <q-item-section avatar>
              <q-icon
                name="home"
                color="accent"
              />
            </q-item-section>

            <q-item-section>
              Área do cliente
            </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            v-ripple
            clickable
            class="text-secondary"
            to="/perfil"
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
            class="text-secondary"
            to="/vendedor"
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
            class="text-secondary"
            to="/suporte"
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
        <!-- Home button -->
        <q-toolbar-title class="row">
          <q-btn
            color="primary"
            unelevated
            label="Nolv"
            @click="$router.push('/')"
          />
          <!-- Search bar -->
          <q-input
            v-model="searchText"
            autofocus
            filled
            class="col-grow q-py-xs q-pl-md"
            bg-color="white"
            label="Pesquisar..."
            @keypress.enter="searchProduct(searchText)"
          >
            <template #append>
              <q-icon
                name="search"
                class="cursor-pointer"
                @click="searchProduct(searchText)"
              />
            </template>
          </q-input>
        </q-toolbar-title>

        <!-- Botão de login e perfil -->
        <q-btn-dropdown
          v-if="clientName !== ''"
          color="primary"
          unelevated
          icon="person"
          :label="clientName"
        >
          <q-list>
            <q-item
              v-close-popup
              clickable
              @click="$router.push('/cliente')"
            >
              <q-item-section>
                <q-item-label>Compras</q-item-label>
              </q-item-section>
            </q-item>

            <q-item
              v-close-popup
              clickable
              @click="$router.push('/perfil')"
            >
              <q-item-section>
                <q-item-label>Meu perfil</q-item-label>
              </q-item-section>
            </q-item>

            <q-item
              v-close-popup
              clickable
              @click="logout()"
            >
              <q-item-section>
                <q-item-label>Sair</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
        <q-btn
          v-else
          color="primary"
          unelevated
          icon="person"
          label="Entrar"
          @click="$router.push('/login')"
        />
        <!-- Shopping Cart icon -->
        <q-btn
          color="accent"
          unelevated
          icon="shopping_cart"
          round
          @click="$router.push('/pagamento')"
        >
          <q-badge
            v-if="cartItems !== 0"
            color="red"
            floating
          >
            {{ cartItems }}
          </q-badge>
        </q-btn>
      </q-toolbar>

      <!-- Toolbar mobile -->
      <q-toolbar class="q-gutter-xs lt-sm">
        <!-- // TODO logo reduzida -->
        <q-btn
          class="col-1"
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
          @keypress.enter="searchProduct(searchText)"
        >
          <template #append>
            <q-icon
              name="search"
              class="cursor-pointer"
              @click="searchProduct(searchText)"
            />
          </template>
        </q-input>

        <!-- Botão de login e perfil -->
        <q-btn-dropdown
          v-if="clientName !== ''"
          color="primary"
          unelevated
          icon="person"
          class="col"
        >
          <q-list>
            <q-item
              v-close-popup
              clickable
              @click="$router.push('/cliente')"
            >
              <q-item-section>
                <q-item-label>Compras</q-item-label>
              </q-item-section>
            </q-item>

            <q-item
              v-close-popup
              clickable
              @click="$router.push('/perfil')"
            >
              <q-item-section>
                <q-item-label>Meu perfil</q-item-label>
              </q-item-section>
            </q-item>

            <q-item
              v-close-popup
              clickable
              @click="logout()"
            >
              <q-item-section>
                <q-item-label>Sair</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
        <q-btn
          v-else
          class="col"
          color="primary"
          unelevated
          icon="person"
          label="Entrar"
          @click="$router.push('/login')"
        />
        <q-btn
          class="col-1"
          color="accent"
          unelevated
          icon="shopping_cart"
          round
          size="10px"
          @click="$router.push('/pagamento')"
        />
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>

    <footer class="bg-grey-5 q-pa-sm">
      <div class="text-center text-subtitle2">
        © Nolv - {{ currentYear }}
      </div>
    </footer>
  </q-layout>
</template>

<script>
import { api } from 'src/boot/axios'
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'MainLayout',

  setup () {
    return {
      searchText: ref(''),
      drawer: ref(false),
      miniState: ref(true),
      sessionStarted: ref(false),
      cartItems: ref(0),
      clientName: ref(''),
      currentYear: ref(new Date().getFullYear())
    }
  },

  created () {
    const hasSession = this.$q.cookies.has('authKey')

    if (hasSession === true) {
      const client = this.$q.localStorage.getItem('client')

      if (client) {
        this.clientName = client.name.split(' ')[0]
        this.sessionStarted = true
      }

      const cartItems = this.$q.localStorage.getItem('cart')
      if (cartItems) {
        this.cartItems = cartItems.length
      }
    } else {
      this.logout()
    }

    console.log('nova sessão criada')
    console.log('carrinho: ' + JSON.stringify(this.$q.localStorage.getItem('cart')))
    console.log('cliente ' + JSON.stringify(this.clientName))

    window.addEventListener('search-products', (event) => {
      this.searchText = event.detail.searchText
      console.log('evento de busca: ' + this.filter)
    }, false)
  },

  mounted () {
    console.log('layout montado')
    window.addEventListener('modify-cart', (event) => {
      this.cartItems = event.detail.productQtd
    })

    window.addEventListener('client-localstorage-changed', (event) => {
      this.sessionStarted = true

      this.clientName = event.detail.client.name.split(' ')[0]
      console.log('client: ' + this.clientName)
    })
  },

  methods: {
    searchProduct (searchText) {
      console.log('procurar produto: ' + JSON.stringify(searchText))
      window.location.href = '/?s=' + searchText
    },
    logout () {
      console.log('logout clicado')
      const client = this.$q.localStorage.getItem('client')
      api.post('/logout', { email: client.email })
        .then((response) => {
          if (response.data.success === true) {
            this.$q.localStorage.clear()
            this.$q.cookies.remove('authKey')
            this.clientName = ''
            this.cartItems = 0
            this.$router.push('/')
            this.sessionStarted = false
          } else {
            this.showMessage('Logout falhou', 'negative', 'error')
          }
        })
        .catch((error) => {
          console.error('erro: ' + error.message)
          this.showMessage('Logout falhou', 'negative', 'error')
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
