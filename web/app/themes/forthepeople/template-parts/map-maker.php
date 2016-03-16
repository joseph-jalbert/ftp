<?php
/**
 * The template used for making the map in content-all-office-locations.php
 *
 * @package ForThePeople
 */
 
      	$office_locations = get_posts("post_type=office&orderby=title&order=ASC&posts_per_page=-1");  
		
		if( $office_locations ) : ?>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.2.30/gmaps.min.js"></script>
<script src="/wp-content/themes/forthepeople/assets/js/fullofficemap.js"></script>
        <div class="row-fluid"><div class="span6">
    			<?php $break = (int) ceil(count($office_locations) / 2); $n = 0; foreach( $office_locations as $post ) : $n++; ?>
                	<address itemtype="http://schema.org/Attorney" itemscope="">
						<strong><?php echo esc_html(get_the_title()); ?>, <?php echo esc_html(get_field('state')); ?></strong>
							<p>
								<span itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address">
									<span itemprop="streetAddress"><?php echo esc_html(get_field('street_address')); ?><br>
                                    <?php if(get_field('suite_information')) { ?><?php echo esc_html(get_field('suite_information')); ?><br> <?php } ?>
                                    </span>
									<span itemprop="addressLocality"><?php echo esc_html(get_the_title()); ?></span>, <span itemprop="addressRegion"><?php echo esc_html(get_field('state')); ?></span> <span itemprop="postalCode"><?php echo esc_html(get_field('zip_code')); ?></span>
								</span>
								<br><span itemprop="telephone"><?php $phone = get_field('telephone'); echo esc_html("(".substr($phone, 0, 3).") ".substr($phone, 3, 3)."-".substr($phone,6)); ?></span>
							</p>
									<button onclick="mapOffice(<?php echo esc_js($n); ?>);" class="btn btn-small"><i class="icon-map-marker"></i> Map Office</button>
							</address>
                            
                <?php if  ($n === $break) echo '</div><div class="span6">';  ?>
    			<?php endforeach; ?>
                </div></div>
                
    			<script>
				jQuery(document).ready(function () {
        			<?php $i = -1; $mapid = 0; foreach( $office_locations as $post ) : $i++; $mapid++; ?>
					
					<?php $shortdesc = get_field('short_description');
						  $shortdescnew = str_replace("'","&#39;",$shortdesc);
					
					 ?>
            
                	map.addMarker({
                            	index: <?php echo esc_js($i); ?>,
                		id: <?php echo esc_js($mapid); ?>,
                		lat: <?php echo esc_js(get_field('latitude')); ?>,
                		lng: <?php echo esc_js(get_field('longitude')); ?>,
                		title: '<?php echo esc_js(get_the_title()); ?>',
                		infoWindow: {
                  		  content: '<?php echo json_encode($shortdescnew); ?>'
                		},
               		 click: function (e) {
               		     setMarkerWindowPOS(e);
                		}
                	});
        			<?php endforeach; ?>   
				});
				</script>  
				    
		<?php endif; ?>
