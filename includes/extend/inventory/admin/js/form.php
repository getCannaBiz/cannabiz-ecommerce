<?php

$wpd_item_id = filter_input( INPUT_POST, 'wpd_item_id' );
$metakey     = filter_input( INPUT_POST, 'metakey' );
$metavalue   = filter_input( INPUT_POST, 'metavalue' );

update_post_meta( $wpd_item_id, $metakey, $metavalue );

return "success";
