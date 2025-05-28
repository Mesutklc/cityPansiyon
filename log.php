document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridWeek',
    locale: 'tr',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridWeek,dayGridDay'
    },
    events: 'get_room_status.php',

    eventClick: function(info) {
      // info.event nesnesi var, event.extendedProps ile oda bilgisi gelebilir

      var roomId = info.event.extendedProps.roomId; // oda ID'si event props'da varsayalım
      var startDate = info.event.startStr.substring(0,10); // YYYY-MM-DD format

      if (!roomId || !startDate) {
        alert('Oda veya tarih bilgisi eksik.');
        return;
      }

      if (info.event.extendedProps.status === 'boş') {
        // Oda boşsa rezervasyon ekleme sayfasına yönlendir
        window.location.href = `add_reservation.php?room_id=${roomId}&start_date=${startDate}`;
      } else {
        // Doluysa rezervasyon düzenleme sayfasına (id ile)
        var reservationId = info.event.id;
        window.location.href = `edit_reservation.php?reservation_id=${reservationId}`;
      }
    },

    eventDisplay: 'block',
    dayMaxEvents: true,
  });

  calendar.render();
});
