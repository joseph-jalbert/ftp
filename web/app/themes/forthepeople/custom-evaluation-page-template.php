<?php
/**
 * Template Name: Custom Evaluation Page Template
 */


$video_url              = get_field( 'video_url' );
$custom_hubspot_form_id = get_field( 'custom_hubspot_form_id' );
if ( ! $video_url ) :
	$video_url = 'https://www.youtube.com/embed/jpt-J_JHBKs?feature=oembed';
endif;
if ( ! $custom_hubspot_form_id ) {
	$custom_hubspot_form_id = '69d9a0d1-408f-4ba0-9781-1e834effd8c0';
}
$gray_box_title = get_field( 'gray_box_title' );
if ( ! $gray_box_title ) {
	$gray_box_title = 'At Morgan & Morgan, we are dedicated to our clients.';
}
$gray_box_content = get_field( 'gray_box_content' );
if ( ! $gray_box_content ) {
	$gray_box_content = 'Every day, we help people like you get their lives back on track by fighting their legal battles for them.';
}
$verdicts_and_settlements_title = get_field( 'verdicts_and_settlements_title' );

if ( ! $verdicts_and_settlements_title ) {
	$verdicts_and_settlements_title = 'Verdicts &amp; Settlements';
}

$testimonials_title = get_field( 'testimonials_title' );

if ( ! $testimonials_title ) {
	$testimonials_title = 'Recent Client Testimonials';
}


get_header(); ?>


<div class="container force-fullwidth">
	<div class="row-fluid">
		<div id="col0" class="span12">
			<div class="contactus-block">
				<div class="container">
					<div class="row-fluid">
						<div class="span9">
							<h1>
								<?php the_title(); ?> </h1>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="content" class="site-content container">
	<div id="interior-page">
		<div class="row-fluid row-leading row-follow">
			<div id="col1" class="span6">
				<main id="main" class="site-main" role="main">

					<div id="form1" class="contactus">
						<div class="cp-hs-form form-wrapper">

							<script>
								hbspt.forms.create({
									portalId: '1841598',
									formId: '<?php echo esc_js( $custom_hubspot_form_id ); ?>',
									target: '.cp-hs-form',
									onFormReady: function ($form) {
										jQuery("input[name='yes_sign_me_up_for_the_newsletter_']").attr('aria-describedby', 'informed');
										jQuery("input[name='yes_sign_me_up_for_the_newsletter_']").next().attr('id', 'informed');

									},
									onFormSubmit: function ($form) {
										jQuery.each($form.context, function (index, val) {
											var type = jQuery(val).prop('nodeName');
											if (type === 'INPUT' || type === 'TEXTAREA') {
												var value = $form.context[index].value,
													defaultValue = $form.context[index].defaultValue;
												console.log(value, defaultValue);
												$form.context[index].value = value.replace(/\n|\r/g, ' ');
												$form.context[index].defaultValue = defaultValue.replace(/\n|\r/g, ' ');
											}
										});
									}
								});
							</script>

						</div>
					</div>
					<div style="text-align:center;"><img
							src="<?php echo get_template_directory_uri(); ?>/assets/images/media/peer-av-rated-bw.png"
							alt="" height="44" border="0" width="306"></div>

				</main>
				<!-- #main -->

				<div id="verdictScroll" data-practicearea="-1" data-attorney="-1" data-officelocation="-1"
				     data-stateabb="" data-startrow="9" data-type="all" class="widgetWrap section"
				     style="margin-top:70px;">
					<div class="title" role="complementary">
						<span><?php esc_html_e( $verdicts_and_settlements_title ); ?></span></div>
					<div class="body">
						<ul class="verdictList">

							<?php if ( have_rows( 'verdicts_settlements' ) ) : ?>
								<?php while ( have_rows( 'verdicts_settlements' ) ) : the_row(); ?>

									<?php
									$verdict_text = get_sub_field( 'verdict_text' );
									if ( ! $verdict_text ) {
										$verdict_text = 'Verdict';
									}

									?>
									<li>
										<div class="type">
											<span><?php esc_html_e( $verdict_text ); ?></span><?php esc_html_e( get_sub_field( 'litigation_type' ) ); ?>
										</div>
										<div
											class="result"><?php esc_html_e( get_sub_field( 'litigation_value' ) ); ?></div>
										<?php echo wp_kses_post( get_sub_field( 'text' ) ); ?>
									</li>
								<?php endwhile; ?>
							<?php endif; ?>

						</ul>
					</div>
					<div class="foot"></div>
					<?php
					if ( (int) get_field( 'office_location' ) ):
						$office_info = new WP_Query( array( 'post__in' => (int) get_field( 'office_location' ) ) );
						if ( $office_info->have_posts() ):
							while ( $office_info->have_posts() ): $office_info->the_post();
								?>
								<div class="">
									<?php

									echo '<div class="widgetWrap aside row-leading">';
									echo '<div class="title"><span>Contact Us</span></div>';
									echo '<div class="body"><div class="row-fluid"><div class="span12"><address itemtype="http://schema.org/Attorney" itemscope="">';
									echo '<strong>';
									echo the_title();
									echo ', ';
									echo esc_html( get_field( 'state' ) );
									echo '</strong>';
									echo '<br /><span itemprop="name">Morgan & Morgan</span>';
									echo '<p><span itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address"><span itemprop="streetAddress">';
									echo esc_html( get_field( ( 'street_address' ) ) );
									if ( get_field( 'suite_information' ) ) {
										echo '<br />';
										echo esc_html( get_field( 'suite_information' ) );
									}
									echo '<br /></span>';
									echo '<span itemprop="addressLocality">';
									// if we have a locality that we want to override, there's an ACF field to override it called state_override
									if ( $locality = get_field( 'state_override' ) ) :
										echo esc_html( $locality );
									else:
										the_title();
									endif;
									echo '</span>';

									echo ', ';
									echo '<span itemprop="addressRegion">';
									echo esc_html( get_field( 'state' ) );
									echo '</span> ';
									echo '<span itemprop="postalCode">';
									echo esc_html( get_field( 'zip_code' ) );
									echo '</span></span><br />';
									echo '<span itemprop="telephone">';
									$phone = esc_html( get_field( 'telephone' ) );
									echo esc_html( "(" . substr( $phone, 0, 3 ) . ") " . substr( $phone, 3, 3 ) . "-" . substr( $phone, 6 ) );
									echo '</span></span></p></address></div></div></div>';
									echo '<div class="foot"></div></div>';

									?>
									<div class="foot"></div>


								</div>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						<?php endif; ?>
					<?php endif; ?>

				</div>
			</div>
			<!-- #col1 -->

			<div id="col2" class="span6">
				<div class="contactus-video">
					<div class="videoWrapper" itemprop="video" itemscope="" itemtype="http://schema.org/VideoObject">
						<div class="fitvid">
							<p>
								<iframe width="640" height="360"
								        src="<?php echo esc_url( $video_url ); ?>" frameborder="0"
								        allowfullscreen></iframe>
							</p>
						</div>

					</div>
				</div>
				<div class="well">
					<p class="no-margin-topbottom"><span
							class="blue bold format italic"><?php esc_html_e( $gray_box_title ); ?></span><br/>
						<?php esc_html_e( nl2br( $gray_box_content ) ); ?></p>
				</div>


				<div id="testimonialScroll" data-practicearea="-1" data-attorney="-1" data-officelocation="-1"
				     data-stateabb="" data-startrow="9" class="widgetWrap section">

					<div class="title"><span><?php esc_html_e( $testimonials_title ); ?></span></div>

					<div class="body">

						<?php if ( have_rows( 'client_testimonials' ) ) : ?>
							<?php while ( have_rows( 'client_testimonials' ) ) : the_row(); ?>


								<div class="testimonial-block">

									<div class="testimonial">
										<?php echo wp_kses_post( get_sub_field( 'testimonial' ) ); ?>
										<small><?php esc_html_e( get_sub_field( 'quote_source' ) ); ?></small>
									</div>


								</div>
							<?php endwhile; ?>
						<?php endif; ?>
						<div class="foot"></div>

					</div>
				</div>
			</div>
		</div>
	</div>


	<?php get_footer(); ?>
