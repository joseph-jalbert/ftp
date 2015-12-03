<?php
/**
 * Template Name: All Office Locations
 */

get_header(); ?>

<div id="interior-page" class="container force-fullwidth">
<div class="row-fluid">
<div id="col0" class="span12">

<div class="officeMap-wrap"><div id="map"></div></div>

</div>
</div>
</div>


<div id="interior-page" class="container">
  <div class="row-fluid row-leading row-follow">
    <div id="col1" class="span8">
      <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part( 'template-parts/content', 'all-office-locations' ); ?>
      <?php endwhile; ?>
    </div>

<div id="col2" class="span4 form-homepage-offset">
<?php get_sidebar(); ?>
</div>
</div>
</div>


<?php get_footer(); ?>
