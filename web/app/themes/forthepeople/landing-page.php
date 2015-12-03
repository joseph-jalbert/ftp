<?php
/**
 * Template Name: Landing Page
 */
 
remove_filter ('the_content', 'wpautop');

get_header('landing');

while ( have_posts() ) : the_post();
the_content();
endwhile;

get_footer('landing'); ?>
