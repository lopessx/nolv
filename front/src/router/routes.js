
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('src/pages/Home.vue') },
      { path: 'login', component: () => import('src/pages/Login.vue') },
      { path: 'pagamento', component: () => import('src/pages/Checkout.vue') },
      { path: 'cliente', component: () => import('src/pages/ClientArea.vue') },
      { path: 'perfil', component: () => import('src/pages/Profile.vue') },
      { path: 'vendedor', component: () => import('src/pages/VendorArea.vue') },
      { path: 'suporte', component: () => import('src/pages/Suport.vue') },
      { path: 'produto/:id', component: () => import('src/pages/Product.vue') },
      { path: 'produto/cadastro', component: () => import('src/pages/ProductRegister.vue') },
      { path: 'download', component: () => import('src/pages/ProductDownload.vue') },
      { path: 'produto/editar/:id', component: () => import('src/pages/ProductEdit.vue') },
      { path: 'registro', component: () => import('src/pages/Register.vue') }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
