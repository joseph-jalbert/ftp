<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package ForThePeople
 */
?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
        <?php if(!is_page_template('all-class-actions.php') && !is_page('in-the-community') && !is_page('featured-news')) { ?>
    	<div class="content-pane-border"></div>
		<h1 class="pagetitle"><?php the_field('page_title'); ?></h1>
        <div class="subtitle"><?php the_field('sub_title'); ?></div>
        <div class="heading-hr"></div>
        <?php } ?>
        
        <?php if(!is_page_template('all-class-actions.php') && !is_page_template('class-action-cats.php') && !is_page('open-lawsuits') && !is_page('practice-areas') && !is_page('featured-news')) { ?>
        <div class="socialmediawidget vertical offpage">
			<span class='st_plusone_hcount' displayText='Google +1'></span>
			<span class='st_facebook_hcount' displayText='Facebook'></span>
			<span class='st_twitter_hcount' displayText='Tweet'></span>
			<span class='st_email_hcount' displayText='Email'></span>
		</div>
        <?php } ?>
        
        <?php if(get_field('jump_box') ) { ?>
        <div class="well pull-right span4 offset1">
        	<?php the_field('jump_box'); ?>
        </div>
		
		<?php } the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'forthepeople' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( esc_html__( 'Edit', 'forthepeople' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
