<template>
  <q-page>
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
        <q-input
          v-model="description"
          filled
          type="textarea"
          label="Descrição do produto"
        />
      </div>
    </div>

    <div class="row col-12 q-px-xl q-py-md">
      <div class="col-6">
        <q-input
          v-model="productName"
          label="Nome do produto"
        />
        <q-input
          v-model="version"
          prefix="v"
          label="Versão"
        />
        <div class="q-py-md">
          <q-rating
            v-model="ratingModel"
            readonly
            size="2em"
            :max="5"
            color="accent"
          />
          {{ ratingQtd }}
        </div>
      </div>
      <div class="row col-6 justify-center">
        <q-input
          v-model="price"
          prefix="R$"
          label="Preço"
        />
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
            <q-item-label>
              <q-input
                v-model="language"
                label="Idioma"
              />
            </q-item-label>
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
            <q-item-label>
              <q-select
                v-model="category"
                filled
                :options="categoryOptions"
                label="Categoria"
              />
            </q-item-label>
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
            <q-item-label>
              <q-select
                v-model="os"
                filled
                :options="osOptions"
                label="Sistema operacional"
              />
            </q-item-label>
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

    <div class="row q-px-xl q-py-md justify-center items-center">
      <q-btn
        color="accent"
        label="Salvar"
      />
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'ProductEdit',
  setup () {
    const items = ref([{}, {}, {}])

    return {
      productName: ref('Meu produto'),
      languages: ref('Português'),
      slide: ref(1),
      drawer: ref(false),
      miniState: ref(true),
      version: ref('1.0.0'),
      price: ref(1025.50),
      ratingModel: ref(2.5),
      ratingQtd: ref(120),
      os: ref('Windows'),
      language: ref('Português (PT-br)'),
      category: ref('Entretenimento'),
      categoryOptions: ref(['Entretenimento', 'Design', 'Produtividade']),
      osOptions: ref(['Windows', 'Linux', 'MacOS']),
      items,
      onLoad (index, done) {
        setTimeout(() => {
          items.value.push({}, {}, {}, {})
          done()
        }, 2000)
      },
      description: ref('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?')
    }
  },
  mounted () {
    this.price = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(this.price)
    this.price = this.price.replace(/R\$/gm, '')
    this.price = this.price.replace(/\s/gm, '')
  }
})
</script>

<style>

</style>
