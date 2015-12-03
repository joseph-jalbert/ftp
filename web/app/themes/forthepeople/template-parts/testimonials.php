<?php
/**
 * @package ForThePeople
 */


// Find recent testimonials.
$args = array(
	'post_type' => 'testimonial',
	'posts_per_page' => 7, 
	'orderby' => 'date',
	'order' => 'asc'	
);

?>


<div id="testimonialScroll" data-practicearea="-1" data-attorney="-1" data-officelocation="-1" data-stateabb="" data-startrow="9" class="widgetWrap section">
	
	<div class="title"><span>Recent Client Testimonials</span></div>
	
	<div class="body">
		
		<div class="testimonial-block">
				
			<?php
				
				// Start the query
				$the_query = new WP_Query( $args );

				// The Loop
				if ( $the_query->have_posts() ) {
					
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
												
						echo '<div class="testimonial">';
						
							echo '<p>' . get_field('testimonial_text') . '</p>';
							echo '<small>' . get_the_title() . ' from ' . get_field('city') . ', ' . get_field('state') . '</small>';
						
						echo '</div>';
					}	
				} else {
					// no posts found
				}
				/* Restore original Post Data */
				wp_reset_postdata();
				
			?>

		</div>

	</div>

	<div class="foot"></div>
		
</div>
