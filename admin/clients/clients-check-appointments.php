<?php
if (!class_exists('AppointmentsTable')) {
    require_once(WP_PLUGIN_DIR . '/appointments-plugin/admin/clients/tables.php');
}
?>

<div class="wrap">
    <h1>Programări</h1>
    <br>
    <div id="poststuff">
        <div id="post-body">

            <div id="postbox-container-2" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">

                    <div class="postbox">
                        <h2 class="hndle ui-sortable-handle">Programări</h2>
                        <div class="inside">
                            <?php
                            $appointmentsTable = new AppointmentsTable();
                            ?>
                            <form method="post" id="search">
                                <?php
                                $appointmentsTable->prepare_items();
                                $appointmentsTable->display(); ?>
                            </form>
                        </div>
                    </div>

                </div>
                <div id="advanced-sortables" class="meta-box-sortables ui-sortable"></div>
            </div>

        </div>
    </div>
</div>