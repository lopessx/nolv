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

    <div class="row q-px-xl q-pt-lg q-gutter-sm">
      <div class="col-6">
        <q-carousel
          v-model="slide"
          animated
          navigation
          infinite
          arrows
          transition-prev="slide-right"
          transition-next="slide-left"
        >
          <q-carousel-slide
            :name="1"
            img-src="https://cdn.quasar.dev/img/mountains.jpg"
          />
          <q-carousel-slide
            :name="2"
            img-src="https://cdn.quasar.dev/img/parallax1.jpg"
          />
          <q-carousel-slide
            :name="3"
            img-src="https://cdn.quasar.dev/img/parallax2.jpg"
          />
          <q-carousel-slide
            :name="4"
            img-src="https://cdn.quasar.dev/img/quasar.jpg"
          />
        </q-carousel>
      </div>
      <div class="col-5">
        <div class="text-body2">
          {{ description }}
        </div>
      </div>
    </div>

    <div class="row col-12 q-px-xl q-py-md">
      <div class="col-6">
        <div class="text-h5">
          {{ productName }}
        </div>
        <div class="text-subtitle1">
          v {{ version }}
        </div>
        <q-rating
          v-model="ratingModel"
          readonly
          size="2em"
          :max="5"
          color="accent"
        />
        {{ ratingQtd }}
      </div>
      <div class="row col-6 justify-center items-center">
        <div class="col-6 justify-center">
          <q-btn
            color="accent"
            label="Baixar"
          />
        </div>
      </div>
    </div>

    <q-separator color="grey" />

    <div class="row q-px-xl q-py-md justify-center">
      <q-list class="col-7">
        <q-item>
          <q-item-section>
            <q-item-label>Idioma</q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-item-label>Português (PT-br)</q-item-label>
          </q-item-section>
        </q-item>

        <q-separator
          spaced
          inset
          color="grey"
        />

        <q-item>
          <q-item-section>
            <q-item-label>Categoria</q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-item-label>Entretenimento</q-item-label>
          </q-item-section>
        </q-item>

        <q-separator
          spaced
          inset
          color="grey"
        />

        <q-item>
          <q-item-section>
            <q-item-label>Sistema operacional</q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-item-label>Windows</q-item-label>
          </q-item-section>
        </q-item>

        <q-separator
          spaced
          inset
          color="grey"
        />
      </q-list>
    </div>

    <q-separator color="grey" />

    <div class="row q-px-xl q-py-md">
      <div class="text-h6">
        Comentários
      </div>
      <div class="row col-12 q-py-md">
        <div class="col-12">
          <q-input
            type="textarea"
            outlined
            label="Insira seu comentário"
          />
        </div>
        <div class="col-6 q-py-md">
          <q-rating
            v-model="ratingModelClient"
            size="2em"
            :max="5"
            color="accent"
          />
        </div>
        <div class="row col-6 q-py-md justify-end">
          <q-btn
            color="primary"
            label="Enviar"
          />
        </div>
      </div>

      <q-list class="col-12">
        <q-infinite-scroll
          :offset="250"
          @load="onLoad"
        >
          <div
            v-for="(item, index) in items"
            :key="index"
          >
            <q-item>
              <q-item-section>
                <q-item-label>Nome do cliente</q-item-label>
                <q-item-label caption>
                  Comentário completo. Lorem ipsum dolor sit amet, consectetur adipiscit elit.
                </q-item-label>
              </q-item-section>

              <q-item-section
                side
                top
              >
                <q-item-label caption>
                  12/10/2020
                </q-item-label>
                <div class="text-orange">
                  <q-rating
                    v-model="ratingModel"
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
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'ProductDownload',
  setup () {
    const items = ref([{}, {}, {}])

    return {
      ratingModelClient: ref(0),
      productName: ref('Meu produto'),
      languages: ref('Português'),
      slide: ref(1),
      drawer: ref(false),
      miniState: ref(true),
      version: ref('1.0.0'),
      price: ref(1025.50),
      ratingModel: ref(2.5),
      ratingQtd: ref(120),
      items,
      onLoad (index, done) {
        setTimeout(() => {
          items.value.push({}, {}, {}, {})
          done()
        }, 2000)
      },
      description: ref('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?')
    }
  }
})
</script>

<style>

</style>
