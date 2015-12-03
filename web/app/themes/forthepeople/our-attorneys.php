<?php
/**
 * Template Name: Our Attorneys Page
 */

get_header(); ?>
<div id="content" class="site-content container">
<div id="interior-page">
  <div class="row-fluid row-leading row-follow">
    <div id="col1" class="span8">
      <main id="main" class="site-main" role="main">
        <?php while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'template-parts/content', 'attorneys' ); ?>
        <?php endwhile; ?>
      </main>
    </div>
    <div id="col2" class="span4">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
