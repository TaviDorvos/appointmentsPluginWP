<form id="disabled-dates-form" method="post">
    <div class="form-group">
        <input type="hidden" name="action" value="add_disabled_date_process">
        <input id="input-disabled-date" type="hidden" name="input-disabled-date">
        <p style="font-size: 24px;"><b>Selectează o dată pe care vrei să o dezactivezi:</b></p>
        <div id="datepicker-disabled-dates"></div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Dezactivează</button>
    </div>
</form>

<?php

global $wpdb;

$allDatesQuery = "SELECT date FROM date_dezactivate";
$allDates = $wpdb->get_results($allDatesQuery, ARRAY_A);

?>
<div style="font-size: 25px;" class="mt-4"><b>Listă cu toate datele dezactivate:</b></div>
<?php
foreach ($allDates as $result) {
    $disabledDatesArray[] = array(
        'date' => $result['date']
    );
?>
    <div>Data: <b><?php echo $result['date']; ?></b></div>
<?php
}
?>