<?php

/**

 * Template Name: Practice Area Inner Page

 */



get_header(); ?>

<div id="content" class="site-content container">

<div id="interior-page">

<div class="row-fluid row-leading row-follow">

	<div id="col1" class="span8">

		<main id="main" class="site-main" role="main">



			<?php while ( have_posts() ) : the_post(); ?>



				<?php get_template_part( 'template-parts/content', 'page' ); ?>



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
<?php get_sidebar(); ?>
</div>

</div>

</div>

<?php get_footer(); ?>

