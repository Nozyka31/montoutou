let calendarInfo = document.querySelector(".resaInfo").innerHTML

console.log(calendarInfo)

window.onload = () => {
    let calendarElt = document.querySelector("#calendrier")

    let calendar = new FullCalendar.Calendar(calendarElt, {
        selectable: true,
        dragScroll:true,
        unselectAuto:false,
        unselect:false,
        initialView: 'dayGridMonth',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        headerToolbar: {
            start:'prev today',
            center: 'title',
            end: 'next',
        },
        dateClick: function(info) {
        },
        select: function(info) {
            // console.log('selected ' + info.startStr + ' to ' + info.endStr)
            // startDate.id = info.startStr
            // endDate.id = info.endStr
        },
        //events: calendarInfo,
    })

    // var startDate = new Date(Date.UTC(2021, 10, 15, 0, 0, 0))
    // let endDate = new Date(Date.UTC(2021, 10, 22, 0, 18, 0))

    // calendar.addEvent({
    //     title: 'dynamic event',
    //     start: startDate,
    //     end: endDate,
    //     allDay: true
    //   });

    calendar.render()
}