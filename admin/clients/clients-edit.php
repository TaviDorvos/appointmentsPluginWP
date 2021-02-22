<?php

if (!class_exists('ClientsTable')) {
    require_once(WP_PLUGIN_DIR . '/appointments-plugin/admin/clients/tables.php');
}

global $wpdb;

$id_client = $_GET['id_client'];

$nameCurrentQuery = "SELECT nume FROM clienti WHERE id_client='$id_client'";
$phoneCurrentQuery = "SELECT telefon FROM clienti WHERE id_client='$id_client'";
$emailCurrentQuery = "SELECT email FROM clienti WHERE id_client='$id_client'";

$nameCurrent = $wpdb->get_var($nameCurrentQuery);
$phoneCurrent = $wpdb->get_var($phoneCurrentQuery);
$emailCurrent = $wpdb->get_var($emailCurrentQuery);
?>


<form id="edit-client-form" method="post">
    <input type="hidden" name="action" value="edit_client_form_process">
    <input type="hidden" id="input-id" name="input-id" value=" <?= $id_client ?>"> 
    <div class="form-group col-md-5">
        <label for="input-name">Nume:</label>
        <input type="text" class="form-control" id="input-name" name="input-name" value="<?= $nameCurrent ?>">
    </div>
    <div class="form-group col-md-5">
        <label for="input-email">Email:</label>
        <input type="email" class="form-control" id="input-email" name="input-email" value="<?= $emailCurrent ?>">
    </div>
    <div class="form-group col-md-5">
        <label for="input-phone">Telefon:</label>
        <input type="text" class="form-control" id="input-phone" name="input-phone" value="<?= $phoneCurrent ?>">
    </div>
    <div class="form-group col-md-6">
        <button type="submit" name="update-button" class="btn btn-primary">Actualizează</button>
    </div>
</form>