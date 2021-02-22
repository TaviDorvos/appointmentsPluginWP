<?php

/**
 * @package AppointmentsPlugin
 */
/*
    Plugin Name: Appointments Plugin
    Plugin URI: https://www.linkedin.com/in/octavian-dorvos/
    Description: Plugin which helps you to create appointments.
    Version: 1.0.0
    Author: Tavi Dorvos
    Author URI: https://www.linkedin.com/in/octavian-dorvos/
    License: GPLv2 or later
    Text Domanin: appointments-plugin
*/

// It prevent public user to directly access 
// your .php files through URL
defined('ABSPATH') or die('Died');

function appointments_plugin_installer() {

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    //Creating the database for the clients table
    $clientsTable = "CREATE TABLE IF NOT EXISTS clienti (
        id_client int(11) NOT NULL auto_increment,
        nume text NOT NULL,
        telefon text NOT NULL,
        email text NOT NULL,
        PRIMARY KEY (id_client),
        ) $charset_collate;";

//Creating the database for the appointments table
    $appointmentsTable = "CREATE TABLE IF NOT EXISTS programari (
        id_prog int(11) NOT NULL auto_increment,
        status int NOT NULL,
        date_selected date NOT NULL,
        hour_start time NOT NULL,
        hour_final time NOT NULL,
        date_current datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        subject TEXT NUll,
        message TEXT NULL,
        id_client int(11) NOT NULL.
        PRIMARY KEY (id_prog),
        FOREIGN KEY (id_client) REFERENCES clienti(id_client)
        ) $charset_collate;";

//Creating the database for the disabled dates table
$datesDisabledTable = "CREATE TABLE IF NOT EXISTS date_dezactivate (
    id int(11) NOT NULL auto_increment,
    date date NOT NULL,
    PRIMARY KEY (id)
    ) $charset_collate;";

    dbDelta($clientsTable);
    dbDelta($appointmentsTable);
    dbDelta($datesDisabledTable);
}

register_activation_hook(__FILE__, 'appointments_plugin_installer');

require plugin_dir_path(__FILE__) . 'public/public-functions.php';

require plugin_dir_path(__FILE__) . 'admin/admin-functions.php';
