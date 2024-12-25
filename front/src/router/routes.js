import { Cookies } from 'quasar'

const authSession = (to, from, next) => {
  if (Cookies.has('authKey')) {
    return next()
  } else {
    return next('/restrito')
  }
}

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('src/pages/Home.vue') },
      { path: 'pesquisar', component: () => import('src/pages/Search.vue') },
      { path: 'login', component: () => import('src/pages/Login.vue') },
      { path: 'pagamento', component: () => import('src/pages/Checkout.vue') },
      { path: 'cliente', component: () => import('src/pages/ClientArea.vue'), beforeEnter: authSession },
      { path: 'perfil', component: () => import('src/pages/Profile.vue'), beforeEnter: authSession },
      { path: 'vendedor', component: () => import('src/pages/VendorArea.vue'), beforeEnter: authSession },
      { path: 'suporte', component: () => import('src/pages/Suport.vue'), beforeEnter: authSession },
      { path: 'produto/:id', component: () => import('src/pages/Product.vue') },
      { path: 'produto/cadastro', component: () => import('src/pages/ProductRegister.vue'), beforeEnter: authSession },
      { path: 'produto/:id/download', component: () => import('src/pages/ProductDownload.vue'), beforeEnter: authSession },
      { path: 'produto/editar/:id', component: () => import('src/pages/ProductEdit.vue'), beforeEnter: authSession },
      { path: 'registro', component: () => import('src/pages/Register.vue') }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue')
  },

  {
    path: '/restrito',
    component: () => import('pages/Error401.vue')
  }
]

export default routes
