
let reservations = document.getElementsByClassName("fc-daygrid-event")

let inputStart = document.querySelector(".inputStart")
let inputEnd = document.querySelector(".inputEnd")

// let dataCalendar = {{ data|raw }};
let calendar
let calendarElt

$(function() {

    preResa = []
    $(".js-datepicker").on("change",function(){
        var selected = $(this).val();
        let startDate = selected + ' 00:00:00'
        preResa = {
            id: 12,
            start: startDate,
            end: '2021-11-30 00:00:00',
            title: 'Pre contrat'
        }
        JSON.stringify(preResa)
        // let data = {{ data|raw }}
        let index = 0
        for(let i = 0; i < data.length; i++)
        {
            index++
        }
        data[index] = {
            id: 7,
            start: startDate,
            end: '2021-11-30 00:00:00',
            title: 'Pre contrat'
        }
        dataCalendar = data
    });
    
});


window.onload = () => {
    calendarElt = document.querySelector("#calendrier")

    calendar = new FullCalendar.Calendar(calendarElt, {
        selectable: true,
        dragScroll:true,
        unselectAuto:false,
        unselect:false,
        initialView: 'dayGridMonth',
        locale: 'fr',
        select: function(info) {
            
            document.querySelector(".inputStart").value = info.startStr
            document.querySelector(".inputEnd").value = info.endStr
            //alert('selected ' + info.startStr + ' to ' + info.endStr);
        },
        timeZone: 'Europe/Paris',
        headerToolbar: {
            start:'prev today',
            center: 'title',
            end: 'next',
        },
        events: dataCalendar,
    })

    calendar.render()
}
