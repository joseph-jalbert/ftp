<?php
/**
 * The template used for displaying class action page content 
 *
 * @package ForThePeople
 */
?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
    	<div class="content-pane-border"></div>
		<h1 class="pagetitle"><?php the_field('page_title'); ?></h1>
        <div class="subtitle"><?php the_field('sub_title'); ?></div>
        <div class="heading-hr"></div>
        
        <div class="socialmediawidget vertical offpage">
			<span class='st_plusone_hcount' displayText='Google +1'></span>
			<span class='st_facebook_hcount' displayText='Facebook'></span>
			<span class='st_twitter_hcount' displayText='Tweet'></span>
			<span class='st_email_hcount' displayText='Email'></span>
		</div>
        
        
        <?php if (has_post_thumbnail()) { ?>
    <p>
      <?php $feat_image = wp_get_attachment_url(get_post_thumbnail_id()); ?>
      <img width="603" height="228" alt="<?php the_title(); ?>" src="<?php echo $feat_image; ?>">
    </p>
	<?php } ?>
        <?php if(get_field('jump_box') ) { ?>
        	<?php the_field('jump_box'); ?>
		
		<?php } the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'forthepeople' ),
				'after'  => '</div>',
			) );
		?>
	</div>

	<footer class="entry-footer">
		<?php edit_post_link( esc_html__( 'Edit', 'forthepeople' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
