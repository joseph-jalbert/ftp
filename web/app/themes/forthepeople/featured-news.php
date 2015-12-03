<?php

/**

 * Template Name: Featured News

 */



get_header(); ?>

<div class="container force-fullwidth" id="fullSlider">
  <div class="row-fluid fullSlider-wrap">
    <div id="col0" class="span12"> <?php echo get_new_royalslider(28); ?> </div>
  </div>
</div>
<div class="container">
  <div id="interior-page">
    <div class="row-fluid row-leading row-follow">
      <div id="col1" class="span8">
        <main id="main" class="site-main" role="main">
          <?php while ( have_posts() ) : the_post(); ?>
          <?php get_template_part( 'template-parts/content', 'page' ); ?>
          <?php endwhile; 



$ids = get_field('featured_news_stories', false, false); 
$args = array(
	'post_type' => 'any',
	'post__in' => $ids,
	'orderby' => array( 'menu_order' => 'DESC', 'date' => 'DESC' ),
	'posts_per_page' => -1,
);
$featured_news = new wp_Query( $args );

if ( $featured_news->have_posts() ) {
while ( $featured_news->have_posts() ) {
$featured_news->the_post(); ?>
          <div class="media ca-entry">
            <?php if(get_field('post_image')) { ?>
            <div class="ca-img pull-left"> <img src="<?php echo get_field('post_image'); ?>" class="media-object" alt="<?php echo get_the_title(); ?>"> </div>
            <?php } ?>
            <div class="media-body ca-desc">
              <h3 class="media-heading"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
              <p><?php echo get_the_excerpt(); ?><br>
                <a style="text-decoration:none;" href="<?php echo get_the_permalink(); ?>">[ more ]</a></p>
            </div>
          </div>
          <?php
}	
}
wp_reset_postdata();
?>  
<p></p>
<p><span style="font-size:24px"><a href="/blog/category/firm-news/">See All Firm News</a></span></p>
        </main>
        <!-- #main --> 
        
      </div>
      <div id="col2" class="span4">
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
