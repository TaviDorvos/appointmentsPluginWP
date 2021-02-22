<?php

function admin_scripts() {
  //all styles
  wp_enqueue_style('calendar-style', plugin_dir_url(__DIR__) . 'assets/fullCalendar/main.min.css', array(), _S_VERSION);
  wp_enqueue_style('programari-style-bootstrap', plugin_dir_url(__DIR__) . 'assets/bootstrap/css/bootstrap.min.css', array(), _S_VERSION);
  wp_enqueue_style('programari-style-ui', plugin_dir_url(__DIR__) . 'assets/bootstrap/css/jquery-ui.css', array(), _S_VERSION);
  wp_enqueue_style('programari-style-structure', plugin_dir_url(__DIR__) . 'assets/bootstrap/css/jquery-ui.structure.css', array(), _S_VERSION);
  wp_enqueue_style('programari-style-ui-theme', plugin_dir_url(__DIR__) . 'assets/bootstrap/css/jquery-ui.theme.css', array(), _S_VERSION);
  wp_enqueue_style('programari-style-select2', plugin_dir_url(__DIR__) . 'assets/select2/select2.min.css', array(), _S_VERSION);

  //all scripts
  wp_enqueue_script('calendar-script-main', plugin_dir_url(__DIR__) . 'assets/fullCalendar/main.min.js', array('jquery'), _S_VERSION, true);
  wp_enqueue_script('programari-bootstrap-js', plugin_dir_url(__DIR__) . 'assets/bootstrap/js/bootstrap.min.js', array('jquery'), _S_VERSION, true);
  wp_enqueue_script('programari-jquery-ui', plugin_dir_url(__DIR__) . 'assets/bootstrap/js/jquery-ui.min.js', array('jquery'), _S_VERSION, true);
  wp_enqueue_script('programari-select2-js', plugin_dir_url(__DIR__) . 'assets/select2/select2.min.js', array('jquery'), _S_VERSION, true);

  wp_enqueue_script('calendar-script-admin-calendar', plugin_dir_url(__DIR__) . 'admin/calendar/admin-calendar.js', array(), _S_VERSION, true);
  wp_enqueue_script('disabled-dates-script-admin', plugin_dir_url(__DIR__) . 'admin/dates/disabled-dates.js', array(), _S_VERSION, true);
  wp_enqueue_script('client-edit-script-admin', plugin_dir_url(__DIR__) . 'admin/clients/clients.js', array(), _S_VERSION, true);
}
add_action('admin_enqueue_scripts', 'admin_scripts');

function add_rezervari_plugin_pages() {
  add_menu_page("Programări", "Programări", 'manage_options', 'rezervari-plugin', 'calendar_page', 'dashicons-edit-page', 100);
  add_submenu_page("rezervari-plugin", "Clienți", "Clienți", 'manage_options', 'clients_plugin', 'clients_page', 1);
  add_submenu_page("rezervari-plugin", "Date Dezactivate", "Date Dezactivate", 'manage_options', 'disabled_dates', 'disabled_dates', 2);
  add_submenu_page(null, null, 'Detalii Client', 'manage_options', 'clients_programari', 'clients_programari');
  add_submenu_page(null, null, 'Editează', 'manage_options', 'clients_edit', 'clients_edit');
}
add_action("admin_menu", "add_rezervari_plugin_pages");

// Calendar
function calendar_page() {
  include "calendar/calendar.php";
}

//Clients
function clients_page() {
  include "clients/clients-view-page.php";
}

//See all appointments for every client
function clients_programari() {
  include "clients/clients-check-appointments.php";
}

//Edit Clients
function clients_edit() {
  include "clients/clients-edit.php";
}

//Disabled Dates
function disabled_dates() {
  include "dates/disabled-dates.php";
}


//Insert 'ADD APPOINTMENTS FORM' INTO THE DB
function add_appointment_form_process() {
  global $wpdb;

  $currentPhoneNumber = $_POST['input-phone'];
  $queryPhoneList = "SELECT telefon FROM clienti WHERE telefon='$currentPhoneNumber'";
  $queryResults = $wpdb->get_row($queryPhoneList, ARRAY_A);

  if ($queryResults == null) {
    $results_clients = $wpdb->insert(
      "clienti",
      array(
        "nume" => $_POST['input-name'],
        "telefon" => $_POST['input-phone'],
        "email" => $_POST['input-email'],
      )
    );

    $id_client = $wpdb->insert_id;
  } else {
    $queryGetID = "SELECT id_client FROM clienti WHERE telefon='$currentPhoneNumber'";
    $id_client = $wpdb->get_var($queryGetID);
  }

  $results_appointments = $wpdb->insert(
    "programari",
    array(
      "date_selected" => $_POST['datepicker-add-appointment'],
      "hour_start" => date("H:i", mktime($_POST['hourpicker-start-add-appointment'], $_POST['minutespicker-start-add-appointment'])),
      "hour_final" => date("H:i", mktime($_POST['hourpicker-end-add-appointment'], $_POST['minutespicker-end-add-appointment'])),
      "subject" => $_POST['input-subject'],
      "message" => $_POST['input-message'],
      "status" => 1,
      "id_client" => $id_client
    )
  );

  if ($results_appointments != false || $results_clients != false) {
    echo ("success");
  } else {
    echo ("Database insert failed. Please try again.");
  }

  wp_die();
}

add_action('wp_ajax_add_appointment_form_process', 'add_appointment_form_process');
add_action('wp_ajax_nopriv_add_appointment_form_process', 'add_appointment_form_process');
//finishing inserting 'ADD APPOINTMENTS FORM' into the DB

//AUTOCOMPLETING CLIENTS INTO THE "ADD APPOINTMENTS FORM'
function autocomplete_add_appointment_form_process() {
  global $wpdb;

  $clientInfoQuery = "SELECT * FROM clienti WHERE id_client='$_POST[id_client]'";
  $clientInfo = $wpdb->get_results($clientInfoQuery, ARRAY_A);

  foreach ($clientInfo as $result) {
    $clientsArray = array(
      'name' => $result['nume'],
      'email' => $result['email'],
      'phone' => $result['telefon'],
    );
  }
  echo json_encode($clientsArray);
  wp_die();
}
add_action('wp_ajax_autocomplete_add_appointment_form_process', 'autocomplete_add_appointment_form_process');
add_action('wp_ajax_nopriv_autocomplete_add_appointment_form_process', 'autocomplete_add_appointment_form_process');
//finishing autocomplete


// if (isset($_POST['send-mail'])) {
    // $to = $_POST['email'];
    // $from = email@email.ro;
    // $name = $_POST['nume'];
    // $subject = "Reprogramare";
    // $message = "Sefule".$name.",\n"."Trebuie sa reprogramam";

    // $headers = "From:" . $from;
    // mail($to,$subject,$message,$headers);
// }

//UPDATE APPOINTMENTS FROM 'MODIFY APPOINTMENTS FORM' IN THE DB 
function modify_appointment_form_process() {
  global $wpdb;

  $actualID = $_POST['input-id-modify'];
  $update = $wpdb->update('programari', array(
    "date_selected" => $_POST['datepicker-modify-appointment'],
    "hour_start" => date("H:i", mktime($_POST['hourpicker-start-modify-appointment'], $_POST['minutespicker-start-modify-appointment'])),
    "hour_final" => date("H:i", mktime($_POST['hourpicker-end-modify-appointment'], ($_POST['minutespicker-end-modify-appointment']))),
    "status" => 1,
  ), array(
    'id_prog' => $actualID
  ));

  if ($update != false) {
    echo ("success");
  } else {
    echo ("Database update failed. Please try again.");
  }

  wp_die();
}
add_action('wp_ajax_modify_appointment_form_process', 'modify_appointment_form_process');
add_action('wp_ajax_nopriv_modify_appointment_form_process', 'modify_appointment_form_process');

//DELETING BUTTON IN THE 'MODIFY APPOINTMENTS FORM'
function delete_appointment_form_process() {
  global $wpdb;

  $actualID = $_POST['delete_id'];
  $delete = $wpdb->delete('programari', array('id_prog' => $actualID));

  if ($delete != false) {
    echo ("success");
  } else {
    echo ("Database delete failed. Please try again.");
  }

  wp_die();
}
add_action('wp_ajax_delete_appointment_form_process', 'delete_appointment_form_process');
add_action('wp_ajax_nopriv_delete_appointment_form_process', 'delete_appointment_form_process');

//DISABLING A DATE
function add_disabled_date_process() {
  global $wpdb;

  $input_date = $_POST['input_date'];

  $disabled = $wpdb->insert('date_dezactivate', array(
      'date' => $input_date,
  ));

  if ($disabled != false) {
      echo ("success");
    } else {
      echo ("Database delete failed. Please try again.");
    }

  wp_die();
}
add_action('wp_ajax_add_disabled_date_process', 'add_disabled_date_process');
add_action('wp_ajax_nopriv_add_disabled_date_process', 'add_disabled_date_process');

//Update CLIENTS
function edit_client_form_process() {
  global $wpdb;

  $id_client = $_POST['input-id'];
  $nameUpdated = $_POST['input-name'];
  $phoneUpdated = $_POST['input-phone'];
  $emailUpdated = $_POST['input-email'];

  $tableName = 'clienti';
  $tableData = array(
      'nume' => $nameUpdated,
      'telefon' => $phoneUpdated,
      'email' => $emailUpdated
  );
  $tableWhere = array('id_client' => $id_client);

  $saved = $wpdb->update($tableName, $tableData, $tableWhere);

  if ($saved) {
      echo "success";
  } else {
      echo "Modificarile nu au putut fi salvate!";
  }

  wp_die();
}
add_action('wp_ajax_edit_client_form_process', 'edit_client_form_process');
add_action('wp_ajax_nopriv_edit_client_form_process', 'edit_client_form_process');
