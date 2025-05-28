<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

include "config/db.php";
include "header.php";



?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - Takvim</title>

  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/tr.global.min.js"></script>

  <style>
    #calendar {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgb(0 0 0 / 0.1);
      padding: 15px;
      max-width: 900px;
      margin: 20px auto;
    }
    .fc .fc-toolbar-title {
      font-weight: 700;
      font-size: 1.5rem;
      color: #333;
    }
    .fc .fc-daygrid-event {
      border-radius: 8px !important;
      box-shadow: 0 1px 3px rgb(0 0 0 / 0.2);
      font-size: 0.85rem;
      padding: 3px 6px !important;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .fc .fc-daygrid-day-number {
      font-weight: 600;
      color: #555;
    }
    .fc .fc-col-header-cell-cushion {
      font-weight: 600;
      color: #555;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="text-center mt-4">Rezervasyon Takvimi</h2>

  

  <div id="calendar"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives', // Ücretsiz kullanım için
    locale: 'tr',
    initialView: 'resourceTimelineWeek',
    height: "auto",
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'resourceTimelineDay,resourceTimelineWeek'
    },
    resourceOrder: 'title', // Odaları sırala
    resources: 'get_rooms.php', // JSON olarak oda verisi
    events: 'get_room_status.php', // Yukarıda verdiğimiz olaylar (dolu/boş bloklar)
    eventClick: function(info) {
      const props = info.event.extendedProps;
      if (props.status === 'boş') {
        window.location.href = `add_reservation.php?room_id=${props.roomId}&start_date=${info.event.startStr.substring(0, 10)}`;
      } else {
        window.location.href = `edit_reservation.php?reservation_id=${info.event.id}`;
      }
    },
    eventDidMount: function(info) {
      const customerName = info.event.extendedProps.customer_name || '';
      const balance = info.event.extendedProps.balance || '0.00';

      info.el.innerHTML = `
        <div style="font-size: 13px; color: white;">
          <strong>${info.event.title}</strong><br/>
          <span>${customerName}</span><br/>
          <span>₺${balance}</span>
        </div>`;
    },
    slotLabelFormat: [
      { weekday: 'short', day: 'numeric', month: 'short' }
    ]
  });

  calendar.render();
});
</script>




</body>
</html>
