<?php

/**
 * Template Name: Homepage
 */

remove_filter ('the_content', 'wpautop');

get_header(); ?>
<div class="container force-fullwidth" id="fullSlider">
  <div class="row-fluid fullSlider-wrap">
    <div id="col0" class="span12"> <?php echo get_new_royalslider(1); ?> </div>
  </div>
</div>
<div class="container">
  <div class="row-fluid row-leading row-follow">
    <div id="col1" class="span8">
      <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part( 'template-parts/content', 'homepage' ); ?>
      <?php endwhile; ?>
    </div>
    <div id="col2" class="span4 form-homepage-offset">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<div class="container force-fullwidth">
  <div class="row-fluid">
    <div class="span12" id="col100">
      <div class="row-fluid gray-wrap">
        <div class="container">
          <div class="span7">
            <div class="tabbable tabs-left" id="tabs-vert">
              <ul class="nav nav-tabs">
                <li class=""> <a data-toggle="tab" data-target="#tab-blog" href="javascript:void(0);"> <i class="icon-comments-alt"></i> Blog</a> </li>
                <li class="active"> <a data-toggle="tab" data-target="#tab-testimonials" href="javascript:void(0);"> <i class="icon-quote-left"></i> Testimonials</a> </li>
                <li class=""> <a data-toggle="tab" data-target="#tab-media" href="javascript:void(0);"> <i class="icon-desktop"></i> Media</a> </li>
                <li class=""> <a data-toggle="tab" data-target="#tab-offices" href="javascript:void(0);"> <i class="icon-map-marker"></i> Offices</a> </li>
              </ul>
              <div class="tab-content">
                <div id="tab-blog" class="tab-pane">
                  <h3>From Our Blog</h3>
                  <?php
				  $homepage_blog = get_posts("post_parent=0&posts_per_page=3&orderby=date");
				  if( $homepage_blog ) : ?>
                  <?php foreach( $homepage_blog as $post ) : setup_postdata($post); ?>
                  <div class="media ca-entry">
                    <div class="media-body ca-desc">
                      <h3 class="media-heading"><a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                        </a></h3>
                     <?php echo '<p>' . get_the_excerpt() . '<br><a style="text-decoration:none;" href="' . get_the_permalink() . '">[ more ]</a></p>'; ?>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <?php wp_reset_postdata(); ?>
                  <?php endif; ?>
                </div>
                <?php the_field('testimonials_tab'); ?>
                <div id="tab-media" class="tab-pane">
                  <h3>Media Spotlight</h3>
<?php 
$featured = get_field('featured_video');
$featuredvid = $featured[0];
$videourl = get_field('video_url', $featuredvid);
$videoposter = get_field('video_poster_url', $featuredvid);
$videodesc = get_field('description', $featuredvid);
$videotranscript = get_field('transcript', $featuredvid);
$post_slug = $post->post_name;
$video_title = get_the_title($featuredvid);
$theterm = get_the_terms($featuredvid,'related_attorney');
$termname = $theterm[0]->name;
?>

                  
                  <div class="well">
                    <div class="videoWrapper">
                      <video id="<?php echo $post_slug; ?>" class="video-js vjs-default-skin vjs-big-play-centered video-playlist" controls preload="auto" width="100%" height="190" poster="<?php echo $videoposter; ?>" data-setup='{}'>
 				<source src="<?php echo $videourl; ?>" type='video/mp4' />
            </video>
                    </div>
                    <div class="video-meta"></div>
                  </div>
                  
                  
                  
                  <ul class="video-playlist unstyled no-margin-no-pad">
                  
                  
<?php 

$playlist = get_field('video_playlist');
$playlistname = $playlist->name;

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
	'order' => 'asc',
	'posts_per_page' => -1,
);

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
                  </ul>
                </div>
                <?php the_field('offices_tab'); ?>
              </div>
            </div>
          </div>
          <?php the_field('verdicts_box'); ?>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
