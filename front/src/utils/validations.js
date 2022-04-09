
export const required = val => !!val || 'Campo obrigatório'
export const emailValidation = val => (validateEmail(val)) || 'Insira um endereço de e-mail válido'
export const phoneValidation = val => (!!val && val.length === 17) || 'Insira um número de telefone válido'
export const codeValidation = val => (!!val && val.length === 6 && (/\d/g).test(val)) || 'Insira um código de acesso válido'
export const expDate = val => (validateExpDate(val)) || 'Insira uma data de expiração válida'
export const cvv = val => (!!val && val.length >= 3) || 'Insira um CVV válido'
export const cardValidation = val => (!!val && val.length >= 16) || 'Insira um número de cartão válido'

function validateEmail (email) {
  const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  return re.test(String(email).toLowerCase())
}

function validateExpDate (date) {
  const splitDate = date.split('/')

  const today = new Date()
  const expDate = new Date('20' + splitDate[1] + '-' + splitDate[0] + '-01')

  if (expDate.getTime() > today.getTime()) {
    return true
  } else {
    return false
  }
}
