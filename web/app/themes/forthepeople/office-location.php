<?php

/**

 * Template Name: Office Location Main

 */



remove_filter ('the_content', 'wpautop');



get_header(); ?>



<div class="container force-fullwidth" id="fullSlider">

<div class="row-fluid fullSlider-wrap">

<div id="col0" class="span12">



<?php
$slidernum = get_field('slider');

echo get_new_royalslider($slidernum); ?>



</div></div></div>



<div class="container" id="interior-page">

<div class="row-fluid row-leading row-follow">

<div id="col1" class="span8">



			<?php while ( have_posts() ) : the_post(); ?>



				<?php get_template_part( 'template-parts/content', 'officelocation' ); ?>


			<?php endwhile; // end of the loop. ?>



		</div>
        
        
<div id="col2" class="span4 form-homepage-offset">

<?php get_sidebar(); ?>

</div>

	</div>

</div>


<?php get_footer(); ?>

