<?php
/**
 * Template Name: Full Width Empty Page
 */
 
get_header(); ?>
<div id="interior-page" class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col0" class="span12">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>