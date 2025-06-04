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
  <div class="d-flex justify-content-center mb-2" id="calendarLegend">
    <span class="badge bg-success me-2">Boş</span>
    <span class="badge bg-danger me-2">Dolu</span>
  </div>
  <div id="calendar"></div>
</div>

<!-- Rezervasyon detayları modalı -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Rezervasyon Detayları</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="eventDetails" class="mb-0"></p>
      </div>
      <div class="modal-footer">
        <a href="#" id="editLink" class="btn btn-primary">Düzenle</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

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
      const endDate = info.event.endStr.substring(0, 10);
      const reservationId = info.event.extendedProps.reservationId;

      if (status === 'boş') {
        window.location.href = `add_reservation.php?room_id=${roomId}&start_date=${startDate}`;
      } else if (reservationId) {
        document.getElementById('eventDetails').textContent = `${info.event.title}\n${startDate} - ${endDate}`;
        document.getElementById('editLink').href = `edit_reservation.php?reservation_id=${reservationId}`;
        var modal = new bootstrap.Modal(document.getElementById('eventModal'));
        modal.show();
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
