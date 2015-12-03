<?php
/**
 * Template Name: Business Litigation Attorney Referrals Page
 */ 

get_header(); ?>
<div id="interior-page" class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col0" class="span12">
      <?php while ( have_posts() ) : the_post(); ?>
<?php if(get_field('jumbotron')) { ?>
<?php the_field('jumbotron'); ?>
<?php } ?>
</div></div></div>

<div id="interior-page" class="container">
  <div class="row-fluid row-leading row-follow">
    <div id="col0" class="span8">
        <div class="socialmediawidget vertical offpage">
			<span class='st_plusone_hcount' displayText='Google +1'></span>
			<span class='st_facebook_hcount' displayText='Facebook'></span>
			<span class='st_twitter_hcount' displayText='Tweet'></span>
			<span class='st_email_hcount' displayText='Email'></span>
		</div>
<?php if(get_field('partnering')) { ?>
<?php the_field('partnering'); ?>
<?php } ?>
</div>
    <div id="col2" class="span4 mtop-405">
		<?php get_sidebar(); ?>
</div></div></div>

<div id="interior-page" class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col0" class="span12">
<?php if(get_field('referral_relationship')) { ?>
<?php the_field('referral_relationship'); ?>
<?php } ?>
    
<?php if(get_field('profitable_relationship')) { ?>
<?php the_field('profitable_relationship'); ?>
<?php } ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
