<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package ForThePeople
 */

get_header(); ?>
<?php $count = $GLOBALS['wp_query']->found_posts ?>
<div id="content" class="site-content container">
<div id="interior-page">
	<div class="row-fluid row-leading row-follow">
        <div id="col1" class="span8">
		<?php if ( have_posts() ) : ?>
			<div class="content-pane-border"></div>
				<h1 class="pagetitle">
                <?php the_archive_title(); ?>
				<span class="badge badge-info pull-right"><?php echo $count; ?> Posts</span>
				</h1>
				<div class="subtitle">Morgan & Morgan Legal Blog</div>
				<div class="heading-hr"></div>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'blog-loop' );
				?>

			<?php endwhile; ?>

			<?php new_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

	</div><!-- #primary -->
<div id="col2" class="span4">
<?php if(in_category('Blog')) { include('template-parts/blog-sidebar.php');  }  ?>
<?php get_sidebar(); ?>
</div>
</div>
</div>
<?php get_footer(); ?>
