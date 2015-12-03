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
						<strong><?php the_title(); ?>, <?php the_field('state'); ?></strong>
							<p>
								<span itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address">
									<span itemprop="streetAddress"><?php the_field('street_address'); ?><br>
                                    <?php if(get_field('suite_information')) { ?><?php the_field('suite_information'); ?><br> <?php } ?>
                                    </span>
									<span itemprop="addressLocality"><?php the_title(); ?></span>, <span itemprop="addressRegion"><?php the_field('state'); ?></span> <span itemprop="postalCode"><?php the_field('zip_code'); ?></span>
								</span>
								<br><span itemprop="telephone"><?php the_field('telephone'); ?></span>
							</p>
									<button onclick="mapOffice(<?php echo $n; ?>);" class="btn btn-small"><i class="icon-map-marker"></i> Map Office</button>
							</address>
                            
                <?php if  ($n === $break) echo '</div><div class="span6">';  ?>
    			<?php endforeach; ?>
                </div></div>
                
    			<script>
				jQuery(document).ready(function () {
        			<?php $i = 0; foreach( $office_locations as $post ) : $i++; ?>
					
					<?php $shortdesc = get_field('short_description');
						  $shortdescnew = str_replace("'","&#39;",$shortdesc);
					
					 ?>
            
                	map.addMarker({
                		index: 0,
                		id: <?php echo $i; ?>,
                		lat: <?php the_field('latitude'); ?>,
                		lng: <?php the_field('longitude'); ?>,
                		title: '<?php the_title(); ?>',
                		infoWindow: {
                  		  content: '<?php echo $shortdescnew; ?>'
                		},
               		 click: function (e) {
               		     setMarkerWindowPOS(e);
                		}
                	});
        			<?php endforeach; ?>   
				});
				</script>  
				    
		<?php endif; ?>
