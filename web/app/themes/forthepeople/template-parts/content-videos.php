<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package ForThePeople
 */
?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
    	<div class="content-pane-border"></div>
		<h1 class="pagetitle"><?php the_field('page_title'); ?></h1>
        <div class="subtitle"><?php the_field('sub_title'); ?></div>
        <div class="heading-hr"></div>
        
        <div class="socialmediawidget vertical offpage">
			<span class='st_plusone_hcount' displayText='Google +1'></span>
			<span class='st_facebook_hcount' displayText='Facebook'></span>
			<span class='st_twitter_hcount' displayText='Tweet'></span>
			<span class='st_email_hcount' displayText='Email'></span>
		</div>
        
        
<?php 
$featured = get_field('featured_video');
$featuredvid = $featured[0];
$videourl = get_field('video_url', $featuredvid);
$videoposter = get_field('video_poster_url', $featuredvid);
$post_slug = $post->post_name;
?>
		<div class="well">
			<div class="videoWrapper">
            <video id="<?php echo $post_slug; ?>" class="video-js vjs-default-skin vjs-big-play-centered video-playlist" controls preload="auto" width="100%" height="280" poster="<?php echo $videoposter; ?>" data-setup='{}'>
 				<source src="<?php echo $videourl; ?>" type='video/mp4' />
            </video>
            </div>
            <div class="video-meta"></div>
        </div>


		<ul class="video-playlist unstyled no-margin-no-pad">
        
<?php

$playlist = get_field('video_playlist');
$playlistname = $playlist->name;

$videotype = get_field('video_type');
$videotypename = $videotype->name;

if($videotypename == "NA" && $playlistname != "NA") {
$args = array(
	'post_type' => 'multimedia',
	'tax_query' => array(
		array(
			'taxonomy' => 'video_playlist',
			'field'    => 'name',
			'terms'    => $playlistname,
		),
	),
	'orderby' => 'date',
	'order' => 'dsc',
	'posts_per_page' => -1,
);

} else if($playlistname == "NA" && $videotypename != "NA") {

$args = array(
	'post_type' => 'multimedia',
	'tax_query' => array(
		array(
			'taxonomy' => 'media_type',
			'field'    => 'name',
			'terms'    => $videotypename,
		),
	),
	'orderby' => 'date',
	'order' => 'dsc',
	'posts_per_page' => -1,
);

} else if($playlistname == "NA" && $videotypename == "NA") {
	
echo 'No Videos Selected';

} else {
	
$args = array(
	'post_type' => 'multimedia',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'media_type',
			'field'    => 'name',
			'terms'    => $videotypename,
		),
		array(
			'taxonomy' => 'video_playlist',
			'field'    => 'name',
			'terms'    => $playlistname,
		),
	),
	'orderby' => 'date',
	'order' => 'dsc',
	'posts_per_page' => -1,
);

}
	
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
while ( $the_query->have_posts() ) {
$the_query->the_post();

$theterm = get_the_terms($post->ID,'related_attorney');
$termname = $theterm[0]->name;
				
?>
        
       		<li data-video="<?php the_field('video_url'); ?>" itemtype="http://schema.org/VideoObject" itemscope="" itemprop="video">
					<div class="row-fluid">
					<div class="span4"><img itemprop="thumbnailUrl" src="<?php the_field('video_poster_url'); ?>" class="thumbnail videoplaylist"></div>
					<div class="span8 meta"><button itemprop="name" class="videoplaylist btn btn-link"><?php the_title(); ?></button><span><?php if($termname != 'NA') { echo $termname; } ?></span>
					<p itemprop="description"><?php the_field('description'); ?></p>
					</div>
					</div>
					<meta content="<?php the_field('video_url'); ?>" itemprop="contentURL">
					<meta content="<?php the_time('c');?>" itemprop="uploadDate">
					<meta content="<?php the_field('transcript'); ?>" itemprop="transcript">
					</li>
                    
                    

<?php
}	
}
wp_reset_postdata();
?>

	</div>

</article>