<?php

/**

 * Template Name: Archived Class Action Cases
 */



get_header(); ?>


<div id="interior-page">

<div class="container">

<div class="row-fluid row-leading row-follow">

	<div id="col1" class="span8">

		<main id="main" class="site-main" role="main">
        
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', 'page' ); ?>
			<?php endwhile; 



$ids = get_field('top_class_action_cases', false, false); 
$selected_cases = implode(',', $ids);
$class_actions = get_posts("post_type=classactionlawyers&post_parent=0&include=$selected_cases&posts_per_page=20");

if( $class_actions ) : ?>
    <?php foreach( $class_actions as $post ) : ?>
    
<div class="media ca-entry">
						
                        	<?php if(get_field('post_image')) { ?>
							<div class="ca-img pull-left">
								<img src="<?php the_field('post_image'); ?>" class="media-object" alt="<?php the_title(); ?>">
							</div>
                            <?php } ?>
						
					<div class="media-body ca-desc">
						<h3 class="media-heading"><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
						<p><?php the_excerpt(); ?>
						<a style="text-decoration:none;" href="<?php the_permalink(); ?>">[ more ]</a></p>
					</div>
				</div>        
    <?php endforeach; ?>
<?php endif; ?>


		</main><!-- #main -->

	</div>
    

<div id="col2" class="span4">
<?php get_sidebar(); ?>
</div>

</div>

</div>
</div>

<?php get_footer(); ?>

