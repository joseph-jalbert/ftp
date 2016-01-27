<?php
/**
 * @package ForThePeople
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
    	<div class="content-pane-border"></div>
		<h1 class="pagetitle"><?php echo get_the_title() ?></h1>
        <div class="subtitle"><?php the_field('sub_title'); ?></div>
        <div class="heading-hr"></div>
                
    <?php if(!in_category('Blog')) { ?>
        <div class="socialmediawidget vertical offpage">
			<span class='st_plusone_hcount' displayText='Google +1'></span>
			<span class='st_facebook_hcount' displayText='Facebook'></span>
			<span class='st_twitter_hcount' displayText='Tweet'></span>
			<span class='st_email_hcount' displayText='Email'></span>
		</div>
        <?php } ?>

		<?php if(get_field('practice_area_page') ) { include('all-practice-areas.php'); } else { ?>

		<?php if(get_field('jump_box') ) { ?>
        <div class="well pull-right span4 offset1">
        	<?php the_field('jump_box'); ?>
        </div>
        <?php } ?>
    <?php if( is_singular('post') || is_singular(Local_News::POST_TYPE)) { ?>
	<div class="blog-post post-page">	
    <?php if (has_post_thumbnail()) { ?>
    <div class="title img">
      <?php $feat_image = wp_get_attachment_url(get_post_thumbnail_id()); ?>
      <img width="603" height="214" src="<?php echo $feat_image; ?>">
    </div>
	<?php } else if (get_field('imported_blog_post_image')) { ?>
    <div class="title img">
      <img width="603" height="214" src="<?php echo get_field('imported_blog_post_image'); ?>">
    </div>
<?php } ?>
        <div id="blogmeta" class="span3 push1 pull-left">
						<ul class="unstyled">
							<li><i class="icon-calendar"></i> <?php the_time(' M j, Y') ?></li>
		  					<li><i class="icon-user"></i> <?php the_author(); ?> </li>
							<?php

							if ( Local_News::POST_TYPE === get_post_type() ) :
								$terms = wp_get_post_terms( get_the_ID(), Location_Taxonomy::CATEGORY_TAXONOMY );
							else :
								$terms = wp_get_post_terms( get_the_ID(), 'category' );
							endif;

							if ( ! is_wp_error( $terms ) && is_array( $terms ) && $terms ) :
								foreach ( $terms as $term ) :
									if ( 'post' === get_post_type() && 'blog' === $term->slug ) :
										continue;
									endif;
									?>
									<li><i class="icon-folder-open"></i> <?php esc_html_e( $term->name ); ?>
									</li><?php

								endforeach;
							endif;

							?>
						</ul>
		</div>
        </div>
        
        <?php } ?>
        
       <?php  the_content(); } ?>

        
	</div><!-- .entry-content -->
</article><!-- #post-## -->
