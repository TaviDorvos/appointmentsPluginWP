<?php

if (!class_exists('ClientsTable')) {
    require_once(WP_PLUGIN_DIR . '/appointments-plugin/admin/clients/tables.php');
}
?>

<div class="wrap">
    <h1>Clienți</h1>
    <br>
    <br>
    <div id="poststuff">
        <div id="post-body">

            <div id="postbox-container-2" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">

                    <div class="postbox">
                        <h2 class="hndle ui-sortable-handle">Clienți</h2>
                        <div class="inside">
                            <?php
                            $clientsTable = new ClientsTable();
                            ?>

                            <form method="post" id="search">
                                <?php
                                $clientsTable->prepare_items();
                                $clientsTable->display(); ?>
                            </form>
                        </div>
                    </div>

                </div>
                <div id="advanced-sortables" class="meta-box-sortables ui-sortable"></div>
            </div>

        </div>
    </div>
</div>