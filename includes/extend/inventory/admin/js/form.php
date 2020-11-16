<?php

$wpd_item_id = $_POST['wpd_item_id'];
$metakey     = $_POST['metakey'];
$metavalue   = $_POST['metavalue'];

update_post_meta( $wpd_item_id, $metakey, $metavalue );

return "success";
