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
					<div class="title" role="complementary"><span>Verdicts &amp; Settlements</span></div>
					<div class="body">
						<ul class="verdictList">

							<?php if ( have_rows( 'verdicts_settlements' ) ) : ?>
								<?php while ( have_rows( 'verdicts_settlements' ) ) : the_row(); ?>

									<li>
										<div class="type">
											<span>Verdict</span><?php esc_html_e( get_sub_field( 'litigation_type' ) ); ?>
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
					<p class="no-margin-topbottom"><span class="blue bold format italic">At Morgan &amp; Morgan, we are dedicated to our clients.</span><br/>
						Every day, we help people like you get their lives back on track by fighting their legal battles
						for them.</p>
				</div>


				<div id="testimonialScroll" data-practicearea="-1" data-attorney="-1" data-officelocation="-1"
				     data-stateabb="" data-startrow="9" class="widgetWrap section">

					<div class="title"><span>Recent Client Testimonials</span></div>

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
