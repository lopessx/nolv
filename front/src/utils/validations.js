const cepPattern = /^.{9,}$/

export const required = val => !!val || 'Campo obrigatório'
export const emailValidation = val => (validateEmail(val)) || 'Insira um endereço de e-mail válido'
export const phoneValidation = val => (!!val && val.length === 17) || 'Insira um número de telefone válido'
export const codeValidation = val => (!!val && val.length === 6 && (/\d/g).test(val)) || 'Insira um código de acesso válido'
export const expDate = val => (validateExpDate(val)) || 'Insira uma data de expiração válida'
export const cvv = val => (!!val && val.length >= 3) || 'Insira um CVV válido'
export const cardValidation = val => (!!val && val.length >= 16) || 'Insira um número de cartão válido'
export const priceValidation = val => (parseFloat(val) > 0) || 'Insira um valor maior que 0'
export const cpfValidation = val => checkCPF(val) || 'Informe um CPF válido'
export const cepValidation = val => cepPattern.test(val) || 'Informe um CEP válido'

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

function checkCPF (cpf) {
  // Verify if paramenter is a string
  if (typeof cpf !== 'string') return false

  // Replace all non-numbers
  cpf = cpf.replace(/[\s.-]*/igm, '')

  // Validates length and pattern
  if (
    !cpf ||
    cpf.length !== 11 ||
    cpf === '00000000000' ||
    cpf === '11111111111' ||
    cpf === '22222222222' ||
    cpf === '33333333333' ||
    cpf === '44444444444' ||
    cpf === '55555555555' ||
    cpf === '66666666666' ||
    cpf === '77777777777' ||
    cpf === '88888888888' ||
    cpf === '99999999999'
  ) {
    return false
  }
  // Declare parameters
  let sum = 0
  let result
  let i = 1

  // Percorre o array de caracteres validando os números antes do dígito verificador
  for (i = 1; i <= 9; i++) { sum = sum + parseInt(cpf.substring(i - 1, i)) * (11 - i) }
  result = (sum * 10) % 11
  if ((result === 10) || (result === 11)) result = 0
  if (result !== parseInt(cpf.substring(9, 10))) return false

  // Reset parameter sum
  sum = 0

  // Validates pattern
  for (i = 1; i <= 10; i++) { sum = sum + parseInt(cpf.substring(i - 1, i)) * (12 - i) }
  result = (sum * 10) % 11
  if ((result === 10) || (result === 11)) result = 0
  if (result !== parseInt(cpf.substring(10, 11))) return false
  // Passes validation return true
  return true
}
