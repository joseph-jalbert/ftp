<?php if ( function_exists( 'yoast_breadcrumb' ) ) : ?>
<div id="breadcrumbs" class="force-fullwidth">
	<div class="row-fluid">
		<div id="col0" class="span12">
			<div class="breadBasket">
				<div class="container">
					<div class="wrapper">
						<?php yoast_breadcrumb(); ?>
					</div>
				</div>
			</div>
			<?php if ((is_child('6110') && !is_page_template('attorney-referrals.php')) || 'business-litigation' == $menuslug || false !== strpos( $post->post_name, 'business-litigation-attorneys' )) { ?>
			<div class="row-fluid btg-content-banner text-center">
            	<img alt="Business Litigation" src="/wp-content/themes/forthepeople/assets/media/images/banners/btg-content-banner.jpg">
            </div>            
            <?php } ?>
			<?php if (in_category('featured-news') && has_post_thumbnail() || is_page('casey-anthony-case') && has_post_thumbnail()) { ?>
            <?php $feat_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
            <div class="row-fluid gray-wrap banner">
				<div class="container">
                	<img alt="<?php the_title(); ?>" src="<?php echo $feat_image; ?>">
                </div>
            </div>
            <?php } ?>
		</div>
	</div>
</div>
<?php endif; ?>