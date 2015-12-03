<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package ForThePeople
 */

get_header(); ?>
<div id="content" class="site-content container">
<div id="interior-page">
	<div class="row-fluid row-leading row-follow">
        <div id="col1" class="span8">
    <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
    <?php get_template_part( 'template-parts/content', 'blog-loop' ); ?>    
    <?php endwhile; ?>
    <?php new_posts_navigation(); ?>
    <?php else : ?>
    <?php get_template_part( 'template-parts/content', 'none' ); ?>
    <?php endif; ?>
	</div>
<div id="col2" class="span4">
<?php if(in_category('Blog')) { include('template-parts/blog-sidebar.php');  }  ?>
<?php get_sidebar(); ?>
</div>
</div>
</div>
</div>
<?php get_footer(); ?>
