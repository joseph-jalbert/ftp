<?php
/**
 * The template for displaying all single posts.
 *
 * @package ForThePeople
 */
get_header(); ?>
<div id="content" class="site-content container">
<div id="interior-page">
	<div class="row-fluid row-leading row-follow">
        <div id="col1" class="span8">
		<main id="main" class="site-main" role="main">
        
		<?php while ( have_posts() ) : the_post(); 
		
		if(is_singular('classactionlawyers')) {
			  get_template_part( 'template-parts/content', 'classaction' );
		} else {
			  get_template_part( 'template-parts/content', 'single' );
		} ?>
            
            <div class="clearfix"></div>


			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				
			?>
            

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<div id="col2" class="span4">

<?php if(in_category('Blog')) { include('template-parts/blog-sidebar.php');  }  ?>
			
<?php get_sidebar(); ?>
<?php if(! in_category('Blog')) { include('template-parts/office-location-sidebar.php');  } ?>
</div>
</div>
</div>
<?php get_footer(); ?>
