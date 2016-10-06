<?php

add_action('admin_init', 'fix_for_yoast_popup');

function fix_for_yoast_popup(){

    update_user_meta( get_current_user_id(), 'wpseo_ignore_tour', 1 );

}