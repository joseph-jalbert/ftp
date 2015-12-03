<?php
/**
 * Template Name: Mesothelioma Attorneys
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<div id="interior-page" class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col0" class="span12">
      <?php if(get_field('jumbotron')) { ?>
	    <?php the_field('jumbotron'); ?>
	  <?php } ?>
    </div>
  </div>
</div>
<div id="interior-page" class="container">
  <div class="row-fluid row-leading row-follow">
    <div id="col1" class="span8">
      <?php the_content(); ?>
    </div>
    <div class="meso">
      <div id="col2" class="span4 form-homepage-offset">
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>
</div>
<div id="interior-page" class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col100" class="span12">
    <?php if(get_field('meso_book')) { ?>
	  <?php the_field('meso_book'); ?>
	<?php } ?>

	<?php if(get_field('types_of_meso_lawsuits')) { ?>
	  <?php the_field('types_of_meso_lawsuits'); ?>
	<?php } ?>

	<?php if(get_field('what_to_expect')) { ?>
	  <?php the_field('what_to_expect'); ?>
	<?php } ?>

	<?php if(get_field('steps_in_filing')) { ?>
	  <?php the_field('steps_in_filing'); ?>
	<?php } ?>
    
	<?php if(get_field('damages')) { ?>
	  <?php the_field('damages'); ?>
	<?php } ?>
    
	<?php if(get_field('quote')) { ?>
	  <?php the_field('quote'); ?>
	<?php } ?>
    </div>
  </div>
</div>
<?php endwhile; ?>
<?php get_footer(); ?>
