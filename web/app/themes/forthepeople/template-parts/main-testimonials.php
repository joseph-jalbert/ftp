<?php
/**
 * @package ForThePeople
 */

// Find recent testimonials.
$args = array(
	'post_type' => 'testimonial',
	'posts_per_page' => 50, 
	'orderby' => 'date',
	'order' => 'asc'	
);

?>

<div id="testimonialScroll" data-practicearea="-1" data-attorney="-1" data-officelocation="-1" data-stateabb="" data-startrow="9" class="widgetWrap section">
  <div class="title"><span>Recent Client Testimonials</span></div>
  <div class="body">
    <div class="testimonial-block">
      <?php
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						echo '<div class="testimonial">';
							echo '<p>' . get_field('testimonial_text') . '</p>';
							echo '<small>' . get_the_title() . ' from ' . get_field('city') . ', ' . get_field('state') . '</small>';
						echo '</div>';
					}	
				} else {
				}
				wp_reset_postdata();
			?>
    </div>
  <button class="btn btn-primary loadmore" id="more_posts">Load More Results</button>
  </div>
  <div class="foot"></div>
</div>


<script type="text/javascript">
jQuery(document).ready(function($) {
    var ajaxUrl = "<?php echo admin_url('admin-ajax.php')?>";
    var page = 1;
    var ppp = 10; 
	var initialoffset = 75;
	var action = 1;

    $("#more_posts").on("click", loadmore);
	function loadmore() {
	if ( action == 1 ) {
        $("#more_posts").attr("disabled",true);
        $.post(ajaxUrl, {
            postoffset: (page * initialoffset) + 1,
            action:"more_testimonials_ajax",
        }).success(function(posts){
            page++;
            $("div.testimonial-block").append(posts);
            $("#more_posts").attr("disabled",false);
        });
	action = 2;
	} else {
        $("#more_posts").attr("disabled",true);
        $.post(ajaxUrl, {
            action:"more_testimonials_ajax",
            postoffset: (page * ppp) + 65 + 1,
        }).success(function(posts){
            page++;
            $("div.testimonial-block").append(posts);
            $("#more_posts").attr("disabled",false);
        });
	}
   };
});
</script> 
