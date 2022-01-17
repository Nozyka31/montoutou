<<<<<<< HEAD
let start = document.getElementById('form_start')
let end = document.getElementById('form_end')
let price = document.getElementById('price')
let dailyPriceComp = document.getElementsByClassName('dailyprice')
let dailyPrice = dailyPriceComp[0].id
console.log(dailyPrice)
window.addEventListener('click', function() {
    startDate = new Date(start.value)
    endDate = new Date(end.value)
    startDate = startDate.getTime() / 86400000
    endDate = endDate.getTime() / 86400000
    if(startDate && endDate)
    {
        let totalPrice = (endDate - startDate) * dailyPrice
        price.innerHTML = "Le prix total de votre réservation est de " + totalPrice + "€."
    }
=======
let start = document.getElementById('form_start')
let end = document.getElementById('form_end')
let price = document.getElementById('price')
let dailyPriceComp = document.getElementsByClassName('dailyprice')
let dailyPrice = dailyPriceComp[0].id
console.log(dailyPrice)
window.addEventListener('click', function() {
    startDate = new Date(start.value)
    endDate = new Date(end.value)
    startDate = startDate.getTime() / 86400000
    endDate = endDate.getTime() / 86400000
    if(startDate && endDate)
    {
        let totalPrice = (endDate - startDate) * dailyPrice
        price.innerHTML = "Le prix total de votre réservation est de " + totalPrice + "€."
    }
>>>>>>> 3c6a267e2b85b27b0a8761cb148e70f581bba2ed
})