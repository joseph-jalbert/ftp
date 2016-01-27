<?php
/**
 * The template used for displaying page content in the blog loop.
 * @package ForThePeople
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-post">
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="title img">
				<?php $feat_image = wp_get_attachment_url( get_post_thumbnail_id() ); ?>
				<a href="<?php echo get_the_permalink(); ?>">
					<img width="603" height="214" src="<?php echo $feat_image; ?>">
				</a>
				<div class="wrap">
					<h2><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
				</div>
			</div>
		<?php } else if ( get_field( 'imported_blog_post_image' ) ) { ?>
			<div class="title img">
				<img width="603" height="214" src="<?php echo get_field( 'imported_blog_post_image' ); ?>">
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
					<li><i class="icon-calendar"></i> <?php the_time( ' M j, Y' ) ?></li>
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
					<?php if ( get_the_tags() ) { ?>
						<li><i class="icon-tags pull-left"></i>
							<p class="pull-left"><?php echo strip_tags( get_the_tag_list( '', ', ', '' ) ); ?></p></li>
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
