<?php
/**
 * The template for displaying search results pages.
 *
 * @package ForThePeople
 */

get_header(); ?>
<div id="content" class="site-content container">
	<div id="interior-page">
		<div class="row-fluid row-leading row-follow">
			<div id="col1" class="span8">
				<main id="main" class="site-main" role="main">

					<?php if ( have_posts() ) : ?>
						<header class="page-header">
							<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'forthepeople' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
						</header><!-- .page-header -->

						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'template-parts/content', 'search' ); ?>
							<?php
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
						<?php endwhile; // end of the loop. ?>

						<?php the_posts_navigation(); ?>

					<?php else : ?>

						<?php get_template_part( 'template-parts/content', 'none' ); ?>

					<?php endif; ?>

				</main>
				<!-- #main -->
			</div>
			<!-- #primary -->
			<div id="col2" class="span4">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>

	<?php get_sidebar(); ?>
	<?php get_footer(); ?>
