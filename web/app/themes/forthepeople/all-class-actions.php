<?php
/**
 * Template Name: All Class Action Cases
 */
get_header(); ?>
<div class="container force-fullwidth" id="fullSlider">
  <div class="row-fluid fullSlider-wrap">
    <div id="col0" class="span12"> <?php echo get_new_royalslider(27); ?> </div>
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
$ids = get_field('top_class_action_cases', false, false);
$classargs = array(
	'posts_per_page'   => 10,
	'post_type'        => 'any',
	'post_parent'      => 0,
	'post__in'	       => $ids,
	'orderby'          => 'post__in'
);
$class_actions = get_posts($classargs);
if( $class_actions ) : ?>
          <?php foreach( $class_actions as $post ) : ?>
          <div class="media ca-entry">
            <?php if (has_post_thumbnail()) { ?>
            <div class="ca-img pull-left">
              <?php $feat_image = wp_get_attachment_url(get_post_thumbnail_id()); ?>
              <img src="<?php echo $feat_image; ?>" class="media-object" alt="<?php the_title(); ?>"> </div>
            <?php } else if(get_field('post_image')) { ?>
            <div class="ca-img pull-left"> <img src="<?php the_field('post_image'); ?>" class="media-object" alt="<?php the_title(); ?>"> </div>
            <?php } ?>
            <div class="media-body ca-desc">
              <h3 class="media-heading"><a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
                </a></h3>
              <p><?php echo get_the_excerpt(); ?><br />
                <a style="text-decoration:none;" href="<?php the_permalink(); ?>">[ more ]</a></p>
            </div>
          </div>
          <?php endforeach; ?>
		    <h3 class="view-all-cases"><a href="/class-action-lawyers/open-lawsuits/">View all cases »</a></h3>
          <?php endif; ?>
        </main>
      </div>
      <div id="col2" class="span4 form-homepage-offset">
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
