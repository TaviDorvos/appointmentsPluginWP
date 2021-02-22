<?php

function appointments_plugin_public_scripts() {

    wp_register_script('appointments-plugin-public-js', plugin_dir_url(__DIR__) . 'public/assets/js/script.js', array(), _S_VERSION, true);

    wp_localize_script('appointments-plugin-public-js', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'disabled_date' => getDisabledDates(),
    ));

    wp_enqueue_script('appointments-plugin-public-js');

    //all styles
    wp_enqueue_style('programari-style-bootstrap', plugin_dir_url(__DIR__) . 'assets/bootstrap/css/bootstrap.min.css', array(), _S_VERSION);
    wp_enqueue_style('programari-style-ui', plugin_dir_url(__DIR__) . 'assets/bootstrap/css/jquery-ui.css', array(), _S_VERSION);
    wp_enqueue_style('programari-style-structure', plugin_dir_url(__DIR__) . 'assets/bootstrap/css/jquery-ui.structure.css', array(), _S_VERSION);
    wp_enqueue_style('programari-style-ui-theme', plugin_dir_url(__DIR__) . 'assets/bootstrap/css/jquery-ui.theme.css', array(), _S_VERSION);

    //all scripts
    wp_enqueue_script('programari-bootstrap-js', plugin_dir_url(__DIR__) . 'assets/bootstrap/js/bootstrap.min.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('programari-jquery-ui', plugin_dir_url(__DIR__) . 'assets/bootstrap/js/jquery-ui.min.js', array('jquery'), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'appointments_plugin_public_scripts');

//getting the disabled dates from the admin
//and deactivate the dates from the front
//to not be selected
function getDisabledDates() {
    global $wpdb;

    $allDatesQuery = "SELECT * FROM date_dezactivate";
    $allDates = $wpdb->get_results($allDatesQuery, ARRAY_A);

    foreach ($allDates as $result) {
        $disabledDatesArray[] = array(
            'id_date' => $result['id'],
            'disabled_date' => $result['date']
        );
    }

    return $disabledDatesArray;
}
getDisabledDates();

//Adding details from the front form into the database
//creating a new client if the phone is detected as new
//create only an appointment if the phone is detected as used
function contact_form_process() {
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
            "date_selected" => $_POST['input-date'],
            "hour_start" => date("H:i", mktime($_POST['hourpicker'], $_POST['minutespicker'])),
            "hour_final" => date("H:i", mktime($_POST['hourpicker'], ($_POST['minutespicker'] + 15))),
            "subject" => $_POST['input-subject'],
            "message" => $_POST['input-message'],
            "status" => 0,
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
add_action('wp_ajax_contact_form_process', 'contact_form_process');
add_action('wp_ajax_nopriv_contact_form_process', 'contact_form_process');

//front-form shortcode
function contact_form() { ?>
    <form id="contact-form" method="post">
        <input type="hidden" name="action" value="contact_form_process">
        <div class="form-group col-md-6">
            <label for="input-name">Nume:</label>
            <input type="text" class="form-control" id="input-name" name="input-name" placeholder="Numele tau...">
        </div>
        <div class="form-group col-md-6">
            <label for="input-email">Email:</label>
            <input type="email" class="form-control" id="input-email" name="input-email" placeholder="Emailul tau...">
        </div>
        <div class="form-group col-md-6">
            <label for="input-phone">Telefon:</label>
            <input type="text" class="form-control" id="input-phone" name="input-phone" placeholder="Nr tau de telefon...">
        </div>
        <div class="form-group col-md-6">
            <label for="input-subject">Subiect:</label>
            <input type="text" class="form-control" id="input-subject" name="input-subject" placeholder="Subiectul...">
        </div>
        <div class="form-group col-md-6">
            <label for="input-mesaj">Mesaj:</label>
            <textarea class="form-control" id="input-message" name="input-message" rows="5"></textarea>
        </div>
        <div class="form-group col-md-6">
            <input id="input-date" type="hidden" name="input-date">
            <div class="has-datepicker row">
                <div id="datepicker" class="col-6">
                </div>
                <div id="timepicker" class="col-6">
                    <select name="hourpicker" type="text" id="hourpicker"></select>
                    <label>:</label>
                    <select name="minutespicker" type="text" id="minutespicker"></select>
                </div>
            </div>
        </div>
        <div class="form-group col-md-6">
            <button type="submit" class="btn btn-primary">Trimite</button>
            <img class="loader" style="display: none;" src="wp-content/plugins/appointments-plugin/public/assets/images/loader.gif">
        </div>
    </form>
<?php }

add_shortcode('appointments_plugin_contact_form', 'custom_contact_form_shortcode');

function custom_contact_form_shortcode() {
    ob_start();
    contact_form();
    return ob_get_clean();
}

?>