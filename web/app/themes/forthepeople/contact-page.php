<?php
/**
 * Template Name: Contact Page
 */

//custom field used for the page subtitle
$subtitle = get_post_meta($post->ID, 'subtitle', true);
$video_url = get_post_meta($post->ID, 'video_url', true);
$video_placeholder_url = get_post_meta($post->ID, 'video_placeholder_url', true);

get_header(); ?>

<div class="container force-fullwidth">
  <div class="row-fluid">
    <div id="col0" class="span12">
      <div class="contactus-block">
        <div class="container">
          <div class="row-fluid">
            <div class="span9">
              <?php while ( have_posts() ) : the_post(); ?>
              <h1>
                <?php the_title(); ?>
              </h1>
              <?php endwhile; // end of the loop. ?>
              <?php if( !empty( $subtitle ) ) : ?>
              <div class="subtitle"><?php echo $subtitle; ?></div>
              <?php endif; ?>
            </div>
            <div class="span3" align="right"> <a name="trustlink" rel="nofollow" href="http://members.trust-guard.com/certificates/1554" target="_blank" onclick="var nonwin=navigator.appName!='Microsoft Internet Explorer'?'yes':'no'; window.open(this.href.replace('http', 'https'),'welcome','location='+nonwin+',scrollbars=yes,width=517,height='+screen.availHeight+',menubar=no,toolbar=no'); return false;" oncontextmenu="var d = new Date(); alert('Copying Prohibited by Law - This image and all included logos are copyrighted by trust-guard \251 '+d.getFullYear()+'.'); return false;"><img name="trustseal" alt="Business Seal" style="border: 0;" src="http://seals.trust-guard.com/business-1554-small-white.gif"></a> </div>
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

	        <?php
	        $hubspot_portal_id = '1841598';
	        $hubspot_form_id = 'bcb2969c-c74f-4849-876a-fc98624fc965';
	        $hubspot_target = '.cp-hs-form';

	        if ( get_field('hubspot_portal_id')) {
		        $hubspot_portal_id = get_field('hubspot_portal_id');
	        }
	        if ( get_field('hubspot_form_id')) {
		        $hubspot_form_id = get_field('hubspot_form_id');
	        }

	        if ( get_field('hubspot_target')) {
		        $hubspot_target = get_field('hubspot_target');
	        }


	        ?>
	        <script>
	          hbspt.forms.create({
	            portalId: '<?php echo esc_js( $hubspot_portal_id );?>',
	            formId: '<?php echo esc_js( $hubspot_form_id );?>',
	            target: '<?php echo esc_js( $hubspot_target );?>',
		        onFormSubmit: <?php echo esc_js( forthepeople_render_hubspot_text_filter_callback() ); ?>
	          });
	        </script>

      </div>
    </div>
    <div style="text-align:center;"> <img src="<?php echo get_template_directory_uri(); ?>/assets/images/media/peer-av-rated-bw.png" alt="" height="44" border="0" width="306"> </div>

  </main>
  <!-- #main -->

  <?php get_template_part( 'template-parts/verdicts' ); ?>
</div>
<!-- #col1 -->

<div id="col2" class="span6">
<?php if( !empty( $video_url ) ) : ?>
<div class="contactus-video">
<div class="videoWrapper" itemprop="video" itemscope="" itemtype="http://schema.org/VideoObject">
<video id="example_video_1" class="video-js vjs-big-play-centered vjs-default-skin" controls preload="auto" width="100%" height="253" poster="<?php echo $video_placeholder_url; ?>" data-setup='{}'>
<source src="<?php echo $video_url; ?>" type='video/mp4' />
</video>
</div>
</div>
<?php endif; ?>
<?php while ( have_posts() ) : the_post(); ?>
<div class="well">
  <?php the_content(); ?>
</div>
<?php endwhile; // end of the loop. ?>
<?php get_template_part( 'template-parts/testimonials' ); ?>
</div>
</div>
</div>
<?php get_footer(); ?>
