
export const required = val => !!val || 'Campo obrigatório'
export const emailValidation = val => (validateEmail(val)) || 'Insira um endereço de e-mail válido'
export const phoneValidation = val => (!!val && val.length === 14) || 'Insira um número de telefone válido'

function validateEmail (email) {
  const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  return re.test(String(email).toLowerCase())
}
