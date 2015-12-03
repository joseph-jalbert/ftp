<?php
/**
 * Template Name: Tampa Alternate
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<div id="interior-page" class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col0" class="span12">
      <div class="fullSlider-wrap clearfix">
        <div id="fullSlider" class="hero rsHor">
          <?php echo get_new_royalslider(31); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="interior-page" class="container">
  <div class="row-fluid row-leading row-follow">
    <div id="col1" class="span8">
      <?php the_content(); ?>
    </div>
    <div id="col2" class="span4">
      <div id="form1" class="form-homepage-offset">
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>
</div>
<div id="interior-page" class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col100" class="span12">
    <?php if(get_field('personal_injury_section')) { ?>
	  <?php the_field('personal_injury_section'); ?>
	<?php } ?>

	<?php if(get_field('contact_us_section')) { ?>
	  <?php the_field('contact_us_section'); ?>
	<?php } ?>

	<?php if(get_field('why_choose_section')) { ?>
	  <?php the_field('why_choose_section'); ?>
	<?php } ?>

	<?php if(get_field('testimonial_section')) { ?>
	  <?php the_field('testimonial_section'); ?>
	<?php } ?>
    </div>
  </div>
</div>
<?php endwhile; ?>
<?php get_footer(); ?>
