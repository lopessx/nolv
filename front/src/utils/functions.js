
function toBrazilianDate (date) {
  let splitDate = date.split('T')
  splitDate = splitDate[0].split('-')
  const brDate = splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0]
  return brDate
}

export { toBrazilianDate }
