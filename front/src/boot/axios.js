import { boot } from 'quasar/wrappers'
import { Cookies } from 'quasar'
import axios from 'axios'

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({
  baseURL: process.env.API,
  headers: {
    'Access-Control-Allow-Origin': '*'
  }
})

const download = axios.create({
  baseURL: process.env.API,
  responseType: 'blob',
  headers: {
    'Access-Control-Allow-Origin': '*'
  }
})

const request = axios.create()

export default boot(({ app }) => {
  api.interceptors.request.use(config => {
    const cookie = Cookies.get('authKey')
    if (cookie) {
      config.headers.Authorization = `bearer ${cookie}`
    }
    return config
  }, error => {
    return Promise.reject(error)
  })

  download.interceptors.request.use(config => {
    const cookie = Cookies.get('authKey')
    if (cookie) {
      config.headers.Authorization = `bearer ${cookie}`
    }
    return config
  }, error => {
    return Promise.reject(error)
  })
  // for use inside Vue files (Options API) through this.$axios and this.$api

  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API

  app.config.globalProperties.$download = download
})

export { api, download, request }
