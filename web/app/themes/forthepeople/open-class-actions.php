<?php

/**

 * Template Name: Class Action - All Open
 */



get_header(); ?>

<div id="content" class="site-content container">
<div id="interior-page">
  <div class="row-fluid row-leading row-follow">
    <div id="col1" class="span8">
      <main id="main" class="site-main" role="main">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <h1 class="pagetitle"><?php the_field('page_title'); ?></h1>
                    <?php the_content(); ?>
                    <div id="pageFilter/"></div>
                    <?php $ids = get_field('open_class_action_cases', false, false);
                    $classargs = array(
                        'posts_per_page'   => -1,
                        'post_type'        => 'classactionlawyers',
                        'post__in'	       => $ids,
                        'orderby'          => 'post__in'
                    );
                    $class_actions = get_posts($classargs);
                    if( $class_actions ) : ?>
                        <ul class="practice-areas two-col clearfix/">
                          <?php foreach( $class_actions as $post ) : ?>
                          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                          <?php endforeach; ?>
    					</ul>
				  <?php endif; ?>
                </div>
            </article>
        <?php endwhile; ?>
      </main>
    </div>
    <div id="col2" class="span4">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
