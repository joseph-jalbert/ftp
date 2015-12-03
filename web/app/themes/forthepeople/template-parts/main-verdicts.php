<?php
/**
 * @package ForThePeople
 */

// Find recent verdicts and settlements.
$args = array(
	'post_type' => 'verdict',
	'posts_per_page' => 75, 
	'meta_key' => 'amount',
	'orderby' => 'meta_value_num',
	'order' => 'dsc'	
);
?>
<div id="verdictScroll" class="widgetWrap section" data-type="all" data-startrow="76" data-stateabb="" data-officelocation="-1" data-attorney="-1" data-practicearea="-1">
  <div class="title"><span>Our Results</span></div>
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
            action:"more_verdicts_ajax",
        }).success(function(posts){
            page++;
            $("ul.verdictList").append(posts);
            $("#more_posts").attr("disabled",false);
        });
	action = 2;
	} else {
        $("#more_posts").attr("disabled",true);
        $.post(ajaxUrl, {
            action:"more_verdicts_ajax",
            postoffset: (page * ppp) + 65 + 1,
        }).success(function(posts){
            page++;
            $("ul.verdictList").append(posts);
            $("#more_posts").attr("disabled",false);
        });
	}
   };
});
</script>
