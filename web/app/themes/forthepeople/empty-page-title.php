<?php
/**
 * Template Name: Empty Page with Title
 */
 
get_header(); ?>
<div id="interior-page">
  <div class="container">
    <div class="row-fluid row-leading row-follow">
      <div id="col1" class="span12">
	    <div class="content-pane-border"></div>
	    <h1 class="pagetitle"> <?php the_title(); ?> </h1>
	    <div class="subtitle"> </div>
	    <div class="heading-hr"></div>
        <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>