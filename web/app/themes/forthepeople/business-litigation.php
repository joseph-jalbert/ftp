<?php
/**
 * Template Name: Business Litigation Page
 */

get_header(); ?>
<div id="interior-page" class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col0" class="span12">
      <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part( 'template-parts/content', 'business-litigation' ); ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
