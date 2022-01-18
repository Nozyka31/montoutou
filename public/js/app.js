let $map = document.querySelector('#map')
let $gardiens = document.querySelector('.gardiens')


class LeafletMap {

    constructor() {
        this.map = null
        this.bounds = []
    }

    async load (element) {
        return new Promise((resolve, reject) => {

            $script('https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', () => {

                this.map = L.map(element)
                L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    id: 'mapbox/streets-v11',
                    accessToken: 'pk.eyJ1Ijoibm96eWthIiwiYSI6ImNrdnMycGFnZDB4ZGoycnFwZW1tc3B1ZngifQ.DjchD33iZEEV9G5kisaVyw'
                }).addTo(this.map);
                resolve()
            })
        })
    }

    addMarker(lat, lng, text) {
    
        let point = [lat, lng]
        this.bounds.push(point)
        return new LeafletMarker (point, text, this.map)
    }

    center() {
        this.map.fitBounds(this.bounds)
    }
}

class LeafletMarker {
    constructor (point, text, map) {
        this.text = text
        this.popup = L.popup({
            autoclose: false,
            closeOnEscapeKey: false,
            closeOnClick: false,
            closeButton: false,
            className: 'marker',
            maxWidth: 400
        })
        .setLatLng(point)
        .setContent(text)
        .addTo(map)
    }


    setActive() {
        this.popup.getElement().classList.add('is-active')
    }

    unsetActive() {
        this.popup.getElement().classList.remove('is-active')
    }

    addEventListener(event, cb) {
        this.popup.addEventListener('add', () => {

            this.popup.getElement().addEventListener(event, cb);
        })
    }

    setContent(text) {
        this.popup.setContent(text)
        this.popup.getElement().classList.add('is-expended')
        this.popup.update()
    }

    resetContent () {
        this.popup.setContent(this.text)
        this.popup.getElement().classList.remove('is-expended')
        this.popup.update()
    }
}

const initMap = async function () {

    let map = new LeafletMap()
    let hoverMarker = null
    let activeMarker = null
    await map.load($map)
    Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
        
        let marker = map.addMarker(item.dataset.lat, item.dataset.lng, item.dataset.price + ' €')
        item.parentNode.parentNode.addEventListener('mouseover', function () {
            if(hoverMarker !== null)
            {
                hoverMarker.unsetActive()
            }
            marker.setActive()
            hoverMarker = marker
        })
        item.parentNode.parentNode.addEventListener('mouseleave', function () {
            if(hoverMarker !== null)
            {
                hoverMarker.unsetActive()
            }
        })
        marker.addEventListener('click', function() {
            if(activeMarker !== marker)
            {
                if(activeMarker !== null)
                {
                    activeMarker.resetContent()
                }
                marker.setContent(item.parentNode.parentNode.innerHTML)
                activeMarker = marker
            }
            else
            {
                let route = item.nextSibling.nextSibling.getAttribute("href")
                window.location.href = route;

            }
        })
    })
    map.center()
    $gardiens.addEventListener('click', function() {
        if(activeMarker !== null)
        {
            activeMarker.resetContent()
        }
    })
}

if($map !== null)
{
    initMap()
}
