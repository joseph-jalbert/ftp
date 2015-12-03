<?php
/**
 * Template Name: Whistleblower Attorneys Page
 */

get_header(); ?>

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
      <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part( 'template-parts/content', 'whistleblower-qui-tam' ); ?>
      <?php endwhile; ?>
    </div>

<div id="col2" class="span4">
<div class="whistleblower">
<?php get_sidebar(); ?>
</div>
</div>
</div>
</div>



<div id="interior-page" class="container force-fullwidth">
<div class="row-fluid">
<div id="col100" class="span12">

<?php if(get_field('types_of_whistleblower_claims')) { ?>
<?php the_field('types_of_whistleblower_claims'); ?>
<?php } ?>

<?php if(get_field('related_resources')) { ?>
<?php the_field('related_resources'); ?>
<?php } ?>

<?php if(get_field('history_of_false_claims_act')) { ?>
<?php the_field('history_of_false_claims_act'); ?>
<?php } ?>

</div>
</div>
</div>




<?php get_footer(); ?>
