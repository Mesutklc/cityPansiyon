<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
include "config/db.php";
include "header.php";
?>

<div class="container">
  <h2 class="text-center mt-4">Rezervasyon Takvimi</h2>
  <div id="calendar"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/tr.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    themeSystem: 'bootstrap5',
    initialView: 'dayGridMonth',
    locale: 'tr',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,timeGridDay'
    },
    height: 'auto',
    events: 'get_room_status.php',

    eventClick: function (info) {
      const status = info.event.extendedProps.status;
      const roomId = info.event.extendedProps.roomId;
      const startDate = info.event.startStr.substring(0, 10);
      const reservationId = info.event.extendedProps.reservationId;

      if (status === 'boş') {
        window.location.href = `add_reservation.php?room_id=${roomId}&start_date=${startDate}`;
      } else if (reservationId) {
        window.location.href = `edit_reservation.php?reservation_id=${reservationId}`;
      }
    },

    eventDidMount: function (info) {
      const raw = info.event.title;
      const cleanTitle = raw.replace(/^(\d+ - )/, '');
      info.el.innerHTML = cleanTitle;

      new bootstrap.Tooltip(info.el, {
        title: raw,
        placement: 'top',
        trigger: 'hover',
        container: 'body'
      });
    }
  });

  calendar.render();
});
</script>

<?php include 'footer.php'; ?>
