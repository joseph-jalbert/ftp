<?php
/**
 * The template used for displaying page content in all-office-locations.php
 *
 * @package ForThePeople
 */
?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
        <?php if(!is_page_template('all-class-actions.php') && !is_page('in-the-community')) { ?>
    	<div class="content-pane-border"></div>
		<h1 class="pagetitle"><?php the_field('page_title'); ?></h1>
        <div class="subtitle"><?php the_field('sub_title'); ?></div>
        <div class="heading-hr"></div>
        <?php } ?>
                		
		<?php the_content(); ?>
		
		<?php include('map-maker.php'); ?>
        
	</div><!-- .entry-content -->
</article><!-- #post-## -->
