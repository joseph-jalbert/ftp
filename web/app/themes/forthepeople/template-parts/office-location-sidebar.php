<?php
/**
 * @package ForThePeople
 */

global $post;
$parents = get_post_ancestors( $post->ID );
$id = ($parents) ? $parents[count($parents)-1]: $post->ID;
$parent = get_post( $id );
$parentslug = $parent->post_name;


$officeinfo = new WP_Query('post_type=office&name='.$parentslug);

while ( $officeinfo->have_posts() ) {
	$officeinfo->the_post();
	echo '<div class="widgetWrap aside row-leading">';
	echo '<div class="title"><span>Contact Us</span></div>';
	echo '<div class="body"><div class="row-fluid"><div class="span12"><address itemtype="http://schema.org/Attorney" itemscope="">';
	echo '<strong>';
	echo the_title();
	echo ', ';
	echo the_field('state');
	echo '</strong>';
	echo '<br /><span itemprop="name">Morgan & Morgan</span>';
	echo '<p><span itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address"><span itemprop="streetAddress">';
	echo the_field('street_address');
	if(get_field('suite_information')) {
	echo '<br />';
	echo the_field('suite_information');
	}
	echo '<br /></span>';
	echo '<span itemprop="addressLocality">';
	if ( $locality = get_field( 'state_override' ) ) :
		echo esc_html($locality);
	else:
		the_title();
	endif;
	echo '</span>';
	echo ', ';
	echo '<span itemprop="addressRegion">';
	echo the_field('state');
	echo '</span> ';
	echo '<span itemprop="postalCode">';
	echo the_field('zip_code');
	echo '</span></span><br />';
	echo '<span itemprop="telephone">';
	$phone = get_field('telephone');
	echo "(".substr($phone, 0, 3).") ".substr($phone, 3, 3)."-".substr($phone,6);
	echo '</span></span></p></address></div></div></div>';
	echo '<div class="foot"></div></div>';
}
wp_reset_postdata();
?>