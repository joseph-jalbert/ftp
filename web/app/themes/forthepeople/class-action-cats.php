<?php

/**

 * Template Name: Class Action Categories

 */



get_header(); ?>


<?php if(get_field('hero_image')) { ?>
<div id="interior-page">

<div class="row-fluid">

<div id="col0" class="span12">

<div class="row-fluid gray-wrap banner">

<div class="container">


<img class="ca-banner" alt="<?php the_title(); ?>" src="<?php the_field('hero_image'); ?>">



</div></div></div></div></div>
<?php } ?>


<div id="interior-page">

<div class="container">

<div class="row-fluid row-leading row-follow">

	<div id="col1" class="span8">

		<main id="main" class="site-main" role="main">
        
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', 'page' ); ?>
			<?php endwhile; 



$ids = get_field('top_class_action_cases', false, false); 

$args = array(
	'post_type' => 'any',
	'post__in' => $ids,
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'post__not_in' => get_option("sticky_posts")
);
$class_action_cat = new wp_Query( $args );

if ( $class_action_cat->have_posts() ) {
while ( $class_action_cat->have_posts() ) {
$class_action_cat->the_post(); ?>

<div class="media ca-entry">
						
                        	<?php if(get_field('post_image')) { ?>
							<div class="ca-img pull-left">
								<img src="<?php the_field('post_image'); ?>" class="media-object" alt="<?php the_title(); ?>">
							</div>
                            <?php } ?>
						
					<div class="media-body ca-desc">
						<h3 class="media-heading"><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
						<p><?php echo get_the_excerpt(); ?><br />
						<a style="text-decoration:none;" href="<?php the_permalink(); ?>">[ more ]</a></p>
					</div>
				</div>        
<?php
}	
}
wp_reset_postdata();
?>


		</main><!-- #main -->

	</div>
    

<div id="col2" class="span4">
<?php get_sidebar(); ?>
</div>

</div>

</div>
</div>

<?php get_footer(); ?>

