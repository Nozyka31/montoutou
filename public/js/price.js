let start = document.getElementById('form_start')
let end = document.getElementById('form_end')
let price = document.getElementById('price')
let dailyPriceComp = document.getElementsByClassName('dailyprice')
let dailyPrice = dailyPriceComp[0].id
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
})