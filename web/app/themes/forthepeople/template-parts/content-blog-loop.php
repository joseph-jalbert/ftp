<?php
/**
 * The template used for displaying page content in the blog loop.
 * @package ForThePeople
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="blog-post">
    <?php if (has_post_thumbnail()) { ?>
    <div class="title img">
      <?php $feat_image = wp_get_attachment_url(get_post_thumbnail_id()); ?>
      <a href="<?php echo get_the_permalink(); ?>">
      	<img width="603" height="214" src="<?php echo $feat_image; ?>">
      </a>
  	  <div class="wrap">
        <h2><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
      </div>
    </div>
	<?php } else if (get_field('imported_blog_post_image')) { ?>
    <div class="title img">
      <img width="603" height="214" src="<?php echo get_field('imported_blog_post_image'); ?>">
  	  <div class="wrap">
        <h2><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
      </div>
    </div>
    <?php } else { ?>
    <div class="title">
	  <div class="wrap">
        <h2><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
      </div>
    </div>
    <?php } ?>
	<div class="row-fluid">
	  <div class="span3">
	    <ul class="unstyled">
		  <li><i class="icon-calendar"></i> <?php the_time(' M j, Y') ?></li>
		  <li><i class="icon-user"></i> <?php the_author(); ?> </li>
          <?php foreach((get_the_category()) as $cat) { ?>
		  <?php if (!($cat->cat_name=='Blog')) echo '<li><i class="icon-folder-open"></i> ' . $cat->cat_name . ' '; } ?></li>
          <?php if(get_the_tags()) { ?>
          <li><i class="icon-tags pull-left"></i><p class="pull-left"><?php echo strip_tags(get_the_tag_list('',', ','')); ?></p></li>
          <?php } ?>
		</ul>
	  </div>
	  <div class="span9 justifytext">
	    <p><?php echo get_the_excerpt(); ?></p>
		<p><a class="btn" href="<?php echo get_the_permalink(); ?>">Read More</a></p>
	  </div>
	</div>
  </div>
</article>
