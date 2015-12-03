<?php
/**
 * Template Name: Landing Page V2
 */
 
remove_filter ('the_content', 'wpautop');

get_header('landing-v2');

while ( have_posts() ) : the_post();
the_content();
endwhile;

get_footer('landing-v2'); ?>
