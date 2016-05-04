<?php
/**
 * @package ForThePeople
 */

// Find recent verdicts and settlements.
$args = array(
	'post_type' => 'verdict',
	'posts_per_page' => 10, 
	'meta_key' => 'amount',
	'orderby' => 'meta_value_num',
	'order' => 'dsc'	
);
?>
<div id="verdictScroll" data-practicearea="-1" data-attorney="-1" data-officelocation="-1" data-stateabb="" data-startrow="9" data-type="all" class="widgetWrap section" style="margin-top:70px;">
  <div class="title" role="complementary"><span>Verdicts &amp; Settlements</span></div>
  <div class="body">
    <ul class="verdictList">
      <?php
				
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						echo '<li>';
							echo '<div class="type">';
								echo '<span>' . get_field('ruling_type') . '</span>';
								echo get_field('practice_area_tag');
							echo '</div>';
							
							echo '<div class="result">';
							$price = get_field('amount');
							echo  is_numeric($price) ? '$' . number_format($price) : $price;
							echo '</div>';
							echo '<p>' . get_field('description') . '</p>';
						echo '</li>';
					}	
				} else {
				}
				wp_reset_postdata();
			?>
    </ul>
  </div>
  <div class="foot"></div>
</div>
