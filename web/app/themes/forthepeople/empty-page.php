<?php
/**
 * Template Name: Empty Page
 */
 
get_header(); ?>
<div id="interior-page">
  <div class="container">
    <div class="row-fluid row-leading row-follow">
      <div id="col1" class="span12">
        <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>