<?php
/**
 * Template Name: Securities Contact Page
 */

//custom field used for the page subtitle
$subtitle = get_post_meta($post->ID, 'subtitle', true);

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
    <?php if ( is_active_sidebar( 'contact_form' ) ) : ?>
    <div id="form1" class="contactus">
      <?php dynamic_sidebar( 'contact_form' ); ?>
    </div>
    <div style="text-align:center;"> <img src="<?php echo get_template_directory_uri(); ?>/assets/images/media/peer-av-rated-bw.png" alt="" height="44" border="0" width="306"> </div>
    <?php endif; ?>
  </main>
  <!-- #main -->
  
</div>
<!-- #col1 -->

<div id="col2" class="span6">
<?php while ( have_posts() ) : the_post(); ?>
  <?php the_content(); ?>
<?php endwhile; // end of the loop. ?>
</div>
</div>
</div>
<?php get_footer(); ?>
