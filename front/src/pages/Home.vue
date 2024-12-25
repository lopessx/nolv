<template>
  <q-page>
    <div class="row justify-center q-pa-md">
      <div class="col-10 q-pa-md">
        <q-carousel
          v-model="slide"
          control-color="accent"
          animated
          navigation
          infinite
          :autoplay="autoplay"
          arrows
          transition-prev="slide-right"
          transition-next="slide-left"
          @mouseenter="autoplay = false"
          @mouseleave="autoplay = true"
        >
          <q-carousel-slide
            :name="1"
            class="column no-wrap flex-center bg-primary"
          >
            <q-icon
              name="style"
              size="56px"
              color="accent"
            />
            <div class="q-mt-md text-center text-white">
              {{ lorem }}
            </div>
          </q-carousel-slide>
          <q-carousel-slide
            :name="2"
            class="column no-wrap flex-center bg-primary"
          >
            <q-icon
              name="live_tv"
              size="56px"
              color="accent"
            />
            <div class="q-mt-md text-center text-white">
              {{ lorem }}
            </div>
          </q-carousel-slide>
          <q-carousel-slide
            :name="3"
            class="column no-wrap flex-center bg-primary"
          >
            <q-icon
              name="layers"
              size="56px"
              color="accent"
            />
            <div class="q-mt-md text-center text-white">
              {{ lorem }}
            </div>
          </q-carousel-slide>
          <q-carousel-slide
            :name="4"
            class="column no-wrap flex-center bg-primary"
          >
            <q-icon
              name="terrain"
              size="56px"
              color="accent"
            />
            <div class="q-mt-md text-center text-white">
              {{ lorem }}
            </div>
          </q-carousel-slide>
        </q-carousel>
      </div>
    </div>
    <q-separator class="bg-accent" />
    <div class="row q-pa-md">
      <div class="col-12 q-px-md text-h4">
        Ofertas
      </div>
      <div class="col-3 q-pa-md">
        <q-card
          dark
          bordered
          class="bg-primary my-card"
        >
          <q-card-section>
            <div class="text-h6">
              Our Changing Planet
            </div>
            <div class="text-subtitle2">
              by John Doe
            </div>
          </q-card-section>

          <q-separator
            dark
            inset
          />

          <q-card-section>
            {{ lorem }}
          </q-card-section>
        </q-card>
      </div>
      <div class="col-3 q-pa-md">
        <q-card
          dark
          bordered
          class="bg-primary my-card"
        >
          <q-card-section>
            <div class="text-h6">
              Our Changing Planet
            </div>
            <div class="text-subtitle2">
              by John Doe
            </div>
          </q-card-section>

          <q-separator
            dark
            inset
          />

          <q-card-section>
            {{ lorem }}
          </q-card-section>
        </q-card>
      </div>
      <div class="col-3 q-pa-md">
        <q-card
          dark
          bordered
          class="bg-primary my-card"
        >
          <q-card-section>
            <div class="text-h6">
              Our Changing Planet
            </div>
            <div class="text-subtitle2">
              by John Doe
            </div>
          </q-card-section>

          <q-separator
            dark
            inset
          />

          <q-card-section>
            {{ lorem }}
          </q-card-section>
        </q-card>
      </div>
      <div class="col-3 q-pa-md">
        <q-card
          dark
          bordered
          class="bg-primary my-card"
        >
          <q-card-section>
            <div class="text-h6">
              Our Changing Planet
            </div>
            <div class="text-subtitle2">
              by John Doe
            </div>
          </q-card-section>

          <q-separator
            dark
            inset
          />

          <q-card-section>
            {{ lorem }}
          </q-card-section>
        </q-card>
      </div>
    </div>
    <q-separator color="accent" />
    <div class="row q-pa-md align-center justify-center text-center">
      <div class="col-6">
        <div class="q-pa-md text-h6">
          Venda no nosso site!
        </div>
        <div>{{ lorem }}</div>
      </div>
      <div class="col-6">
        <q-card
          dark
          bordered
          class="bg-primary my-card"
        >
          <img
            src="https://cdn.quasar.dev/img/mountains.jpg"
            height="300"
          >

          <q-card-section>
            <div class="text-h6">
              Our Changing Planet
            </div>
            <div class="text-subtitle2">
              by John Doe
            </div>
          </q-card-section>

          <q-card-actions align="center">
            <q-btn
              color="accent"
              label="Vender"
              @click="$router.push('/registro')"
            />
          </q-card-actions>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
// eslint-disable-next-line no-unused-vars
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'Home',
  setup () {
    return {
      slide: ref(1),
      autoplay: ref(true),
      lorem: 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Itaque voluptatem totam, architecto cupiditate officia rerum, error dignissimos praesentium libero ab nemo.'

    }
  },
  created () {
  },
  mounted () {
  },
  methods: {
  }
})
</script>
