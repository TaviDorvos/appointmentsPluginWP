<?php

global $wpdb;

$allClients = "SELECT * FROM clienti";
$allClientsResults = $wpdb->get_results($allClients, ARRAY_A);

foreach ($allClientsResults as $result) {

  $clientsArray[] = array(
    'id' => $result['id_client'],
    'name' => $result['nume'],
    'email' => $result['email'],
    'phone' => $result['telefon'],
  );
}

$allEvents = "SELECT * FROM programari";
$allEventsResults = $wpdb->get_results($allEvents, ARRAY_A);

foreach ($allEventsResults as $result) {
  $getClient = "SELECT * FROM clienti WHERE id_client='$result[id_client]'";
  $getClientQuery = $wpdb->get_row($getClient, ARRAY_A);
  if ($result['status'] == "1") {
    $color = '#0cb740';
  } else {
    $color = '#00689f';
  }

  $startTime = explode(":", $result['hour_start']);
  $endTime = explode(":", $result['hour_final']);

  $eventsArray[] = array(
    'id' => $result['id_prog'],
    'title' => $getClientQuery['nume'],
    'start' => $result['date_selected'] . "T" . $result['hour_start'],
    'end' => $result['date_selected'] . "T" . $result['hour_final'],
    'color' => $color,
    'extendedProps' => array(
      'id_client' => $getClientQuery['id_client'],
      'email' => $getClientQuery['email'],
      'phone' => $getClientQuery['telefon'],
      'subject' => $result['subject'],
      'message' => $result['message'],
      'date' => $result['date_selected'],
      'hour_start' => $startTime[0],
      'minute_start' => $startTime[1],
      'hour_end' => $endTime[0],
      'minute_end' => $endTime[1],
    )
  );
}
?>

<div id='calendar'></div>
<br>
<!-- BUTTON TRIGGER FOR ADD APPOINTMENTS -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-appointment">
  Adaugă programare
</button>

<!-- MODAL FOR ADD APPOINTMENTS -->
<div class="modal fade" id="add-appointment" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title-add-appointment">Adaugă programare</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-appointment-form" method="post">
          <input type="hidden" name="action" value="add_appointment_form_process">
          <div class="form-group row">
            <label class="col-2 offset-2" for="input-client">Client:</label>
            <select style="width: 250px;" class="col-6 form-control" type="text" id="input-client" name="input-client">
              <option value="new">Creează client nou</option>
              <?php foreach ($clientsArray as $client) {
                echo "<option value='$client[id]'>" . $client['name'] . "</option>";
              } ?>
            </select>
          </div>
          <div class="form-group row">
            <label class="col-2 offset-2" for="input-name">Nume:</label>
            <input class="col-6 form-control" type="text" id="input-name" name="input-name" required>
          </div>
          <div class="form-group row">
            <label class="col-2 offset-2" for="input-email">Email:</label>
            <input class="col-6 form-control" type="email" id="input-email" name="input-email" required>
          </div>
          <div class="form-group row">
            <label class="col-2 offset-2" for="input-phone">Telefon:</label>
            <input class="col-6 form-control" type="text" id="input-phone" name="input-phone" required>
          </div>
          <div class="form-group row">
            <label class="col-2 offset-2" for="input-subject">Subiect:</label>
            <input class="col-6 form-control " type="text" id="input-subject" name="input-subject">
          </div>
          <div class="form-group row flex-column">
            <label class="col-2 offset-2" for="input-message">Mesaj:</label>
            <textarea class="col-8 offset-2 form-control" id="input-message" name="input-message" rows="3"></textarea>
          </div>
          <div class="form-group row">
            <label class="col-2 offset-2" for="datepicker-add-appointment">Data</label>
            <input class="col-6 form-control" type="text" id="datepicker-add-appointment" name="datepicker-add-appointment" required>
          </div>
          <div id="timepicker-start" class="offset-2">
            <label for="hourpicker-start-add-appointment">Oră început:</label>
            <select name="hourpicker-start-add-appointment" type="text" id="hourpicker-start-add-appointment"></select>
            <label for="minutespicker-start-add-appointment">Minut:</label>
            <select name="minutespicker-start-add-appointment" type="text" id="minutespicker-start-add-appointment"></select>
          </div>
          <br>
          <div id="timepicker-end" class="offset-2">
            <label for="hourpicker-end-add-appointment">Oră sfârșit:</label>
            <select name="hourpicker-end-add-appointment" type="text" id="hourpicker-end-add-appointment"></select>
            <label for="minutespicker-end-add-appointment">Minut:</label>
            <select name="minutespicker-end-add-appointment" type="text" id="minutespicker-end-add-appointment"></select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="add-appointment" class="btn btn-success">Adaugă</button>
          </div>
        </form>
      </div>
      <!--modal body -->
    </div>
    <!--modal content -->
  </div>
  <!--modal dialog -->
</div>
<!--modal fade -->

<!-- MODAL FOR RESCHEDULED APPOINTMENTS -->
<div id="rescheduled-appointments" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content" style="min-width: 900px;">
      <div class="modal-header">
        <h6 id="modal-title-rescheduled-appointment" class="modal-title col-6">Modifică programare</h6>
      </div>
      <div id="modal-body-rescheduled-appointment" class="modal-body row">

        <!-- MODIFICA PROGRAMARE FORM -->
        <form id="modify-appointment-form" class="col-6" method="post">
          <h3>Programează:</h3>
          <input type="hidden" name="action" value="modify_appointment_form_process">
          <input type="hidden" id="input-id-modify" name="input-id-modify">
          <div class="form-group row">
            <label class="col-3" for="input-name-modify">Nume:</label>
            <input class="col-6 form-control" type="text" id="input-name-modify" name="input-name-modify" readonly required>
          </div>
          <div class="form-group row">
            <label class="col-3" for="input-email-modify">Email:</label>
            <input class="col-6 form-control" type="email" id="input-email-modify" name="input-email-modify" readonly required>
          </div>
          <div class="form-group row">
            <label class="col-3" for="input-phone-modify">Telefon:</label>
            <input class="col-6 form-control" type="text" id="input-phone-modify" name="input-phone-modify" readonly required>
          </div>
          <div class="form-group row">
            <label class="col-3" for="input-subject-modify">Subiect:</label>
            <input class="col-6 form-control" type="text" id="input-subject-modify" name="input-subject-modify" readonly>
          </div>
          <div class="form-group row">
            <label class="col-3" for="input-message-modify">Mesaj:</label>
            <textarea class="col-6 form-control" id="input-message-modify" name="input-message-modify" rows="3" readonly></textarea>
          </div>
          <div class="form-group row">
            <label class="col-3" for="datepicker-modify-appointment">Data</label>
            <input class="col-6 form-control" type="text" id="datepicker-modify-appointment" name="datepicker-modify-appointment" required>
          </div>
          <div id="timepicker-start-modify-appointment">
            <label for="hourpicker-start-modify-appointment">Oră început:</label>
            <select name="hourpicker-start-modify-appointment" type="text" id="hourpicker-start-modify-appointment"></select>
            <label for="minutespicker-start-modify-appointment">Minut:</label>
            <select name="minutespicker-start-modify-appointment" type="text" id="minutespicker-start-modify-appointment"></select>
          </div>
          <br>
          <div id="timepicker-end-modify-appointment">
            <label for="hourpicker-end-modify-appointment">Oră sfârșit:</label>
            <select name="hourpicker-end-modify-appointment" type="text" id="hourpicker-end-modify-appointment"></select>
            <label for="minutespicker-end-modify-appointment">Minut:</label>
            <select name="minutespicker-end-modify-appointment" type="text" id="minutespicker-end-modify-appointment"></select>
          </div>
          <br>
          <button type="submit" id="update" class="btn btn-success">Programează</button>
          <button type="submit" id="delete" class="btn btn-danger">Șterge programare</button>
        </form>

        <!-- REPROGRAMREAZA FORM -->
        <form id="appointment-rescheduled-form" class="col-6" method="post">
          <h3>Reprogramează:</h3>
          <input type="hidden" name="action" value="appointment_rescheduled_form_process">
          <div class="form-group row mb-0">
            <label class="col-3" for="datepicker-option1">Optiune 1:</label>
            <input class="col-6 form-control" type="text" id="datepicker-option1" name="datepicker-option1" required>
          </div>
          <div id="timepicker-option1">
            <label for="hourpicker-option1">De la</label>
            <select name="hourpicker-option1" type="text" id="hourpicker-option1"></select>
            <label for="minutespicker-option1">:</label>
            <select name="minutespicker-option1" type="text" id="minutespicker-option1"></select>
          </div>
          <br>
          <div class="form-group row mb-0">
            <label class="col-3" for="datepicker-option2">Optiune 2:</label>
            <input class="col-6 form-control" type="text" id="datepicker-option2" name="datepicker-option2" required>
          </div>
          <div id="timepicker-option2">
            <label for="hourpicker-option2">De la</label>
            <select name="hourpicker-option2" type="text" id="hourpicker-option2"></select>
            <label for="minutespicker-option2">:</label>
            <select name="minutespicker-option2" type="text" id="minutespicker-option2"></select>
          </div>
          <br>
          <div class="form-group row mb-0">
            <label class="col-3" for="datepicker-option3">Optiune 3:</label>
            <input class="col-6 form-control" type="text" id="datepicker-option3" name="datepicker-option3" required>
          </div>
          <div id="timepicker-option3">
            <label for="hourpicker-option3">De la</label>
            <select name="hourpicker-option3" type="text" id="hourpicker-option3"></select>
            <label for="minutespicker-option3">:</label>
            <select name="minutespicker-option3" type="text" id="minutespicker-option3"></select>
          </div>
          <br>
          <button type="submit" name="send-mail" class="btn btn-info">Trimite mail</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!--modal content -->
  </div>
  <!--modal dialog -->
</div>
<!--modal fade -->

<script>
  //FULLCALENDAR
  jQuery(document).ready(function($) {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      height: 1000,
      expandRows: true,
      locale: 'ro',
      initialView: 'timeGridWeek',
      hiddenDays: [0, 1, 3, 6],
      slotMinTime: '08:00:00',
      slotMaxTime: '19:00:00',
      slotLabelInterval: '00:30:00',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek' // buttons for switching between views
      },
      eventClick: function(event, jsEvent, view) {
        $('#rescheduled-appointments').modal();
        $('#input-id-modify').val(event.event.id);
        $('#input-name-modify').val(event.event.title);
        $('#input-email-modify').val(event.event.extendedProps.email);
        $('#input-phone-modify').val(event.event.extendedProps.phone);
        $('#input-subject-modify').val(event.event.extendedProps.subject);
        $('#input-message-modify').val(event.event.extendedProps.message);

        $('#hourpicker-start-modify-appointment').empty();
        $('#minutespicker-start-modify-appointment').empty();
        $('#hourpicker-end-modify-appointment').empty();
        $('#minutespicker-end-modify-appointment').empty();

        $('#datepicker-modify-appointment').val(event.event.extendedProps.date);
        $('#hourpicker-start-modify-appointment').append($("<option/>", {
          value: event.event.extendedProps.hour_start,
          text: event.event.extendedProps.hour_start
        }));
        $('#minutespicker-start-modify-appointment').append($("<option/>", {
          value: event.event.extendedProps.minute_start,
          text: event.event.extendedProps.minute_start
        }));
        $('#hourpicker-end-modify-appointment').append($("<option/>", {
          value: event.event.extendedProps.hour_end,
          text: event.event.extendedProps.hour_end
        }));
        $('#minutespicker-end-modify-appointment').append($("<option/>", {
          value: event.event.extendedProps.minute_end,
          text: event.event.extendedProps.minute_end
        }));
      },
      events: <?php echo json_encode($eventsArray); ?>
    });
    calendar.render();
  });
</script>