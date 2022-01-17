
let startDate = new Date(start)
let endDate = new Date(end)
startDate = startDate.getTime() / 86400000
endDate = endDate.getTime() / 86400000

let price = document.getElementById('price')
let dailyPriceComp = document.getElementsByClassName('dailyprice')
let dailyPrice = dailyPriceComp[0].id

let totalPrice = (endDate - startDate) * dailyPrice
price.innerHTML = "Le prix total de votre réservation est de " + totalPrice + "€."
