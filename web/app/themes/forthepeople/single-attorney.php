<?php
/**
 * Template Name: Practice Area
 */

get_header(); ?>

<div id="profile-wrap">
  <div class="container">
    <div class="row-fluid row-leading row-follow">
      <div id="col1" class="span3">
        <div class="photoFrame">
          <?php $defaultphoto = '/wp-content/themes/forthepeople/assets/media/images/attorneys-orig/nophoto.jpg'; ?>
          <img width="196" height="248" alt="Attorney <?php the_title(); ?>" src="<?php if(get_field('photo')) { the_field('photo'); } else { echo $defaultphoto; } ?>" />

        </div>

        <button data-toggle="modal" id="btnmodalContactTheAttorney" data-target="#modalContactTheAttorney" class="hidden-xs btnrnd contact-attorney"><span>Contact Attorney</span></button>



        <?php if(get_field('quote')) { the_field('quote'); } ?>
        <div class="profile-module"> <span class="heading">Practice Areas</span>
          <ul>
            <?php $attorneyterms = get_the_term_list( $post->ID, 'practice_area', '<li>', '</li><li>', '</li>' ); ?>
            <?php echo strip_tags($attorneyterms, '<li></li>'); ?>
          </ul>
        </div>
        <?php if(get_field('sidebar_module')) { the_field('sidebar_module'); } ?>
      </div>
      <div id="col2" class="span9">
        <div class="profile-head">
          <button data-toggle="modal" id="btnContactAttorney" data-target="#modalContactAttorney" class="btnrnd"><span>Contact Us</span></button>
          <h1><?php the_title(); ?></h1>
          <div class="locations">

            <?php

            $locations         = wp_get_post_terms( $post->ID, 'location' );
            $location_template = '<span class="location"><i class="icon-map-marker"></i> %s</span>';
            if ( is_array( $locations ) ) :
              foreach ( $locations as $location ) :
                echo sprintf( $location_template, esc_html( $location->name ) );
              endforeach;
            endif;

            ?>
          </div>



          <div class="profile-tab-hr"></div>
          <ul class="nav nav-tabs" id="profileTabs">
            <li class="active"><a data-toggle="tab" rel="nofollow" data-target="#tab-biography" href="javascript:void(0);">Biography</a></li>
            <?php if(get_field('tab_1_title')) { ?>
            <li class=""><a data-toggle="tab" rel="nofollow" data-target="#tab2" href="javascript:void(0);"><?php echo get_field('tab_1_title') ?></a></li>
            <?php } ?>
            <?php if(get_field('tab_2_title')) { ?>
            <li class=""><a data-toggle="tab" rel="nofollow" data-target="#tab3" href="javascript:void(0);"><?php echo get_field('tab_2_title') ?></a></li>
            <?php } ?>
            <?php if(get_field('tab_3_title')) { ?>
            <li class=""><a data-toggle="tab" rel="nofollow" data-target="#tab4" href="javascript:void(0);"><?php echo get_field('tab_3_title') ?></a></li>
            <?php } ?>
            <?php if(get_field('tab_4_title')) { ?>
            <li class=""><a data-toggle="tab" rel="nofollow" data-target="#tab5" href="javascript:void(0);"><?php echo get_field('tab_4_title') ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <div id="profileTabContent" class="tab-content">
	 	  <div id="tab-biography" class="tab-pane fade active in">
            <?php while ( have_posts() ) : the_post(); ?>
              <button data-toggle="modal" id="btnmodalContactTheAttorney" data-target="#modalContactTheAttorney" class="visible-xs btnrnd contact-attorney"><span>Contact Attorney</span></button>
        	<?php the_content(); ?>
        	<?php endwhile; ?>
          </div>
          <?php if(get_field('tab_1_content')) { ?>
          <div id="tab2" class="tab-pane fade">
          <?php echo get_field('tab_1_content') ?>
          </div>
          <?php } ?>
          <?php if(get_field('tab_2_content')) { ?>
          <div id="tab3" class="tab-pane fade">
          <?php echo get_field('tab_2_content') ?>
          </div>
          <?php } ?>
          <?php if(get_field('tab_3_content')) { ?>
          <div id="tab4" class="tab-pane fade">
          <?php echo get_field('tab_3_content') ?>
          </div>
          <?php } ?>
          <?php if(get_field('tab_4_content')) { ?>
          <div id="tab5" class="tab-pane fade">
          <?php echo get_field('tab_4_content') ?>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<div id="modalContactAttorney" class="modal hide fade" style="display: none;" aria-hidden="true"><?php dynamic_sidebar( 'attorney_profile_form' ); ?></div>
<div id="modalContactTheAttorney" class="modal hide fade" style="display: none;" aria-hidden="true">
  <div class="inner-modal-contact-the-attorney modal-hs-form"></div>
  <script>
    jQuery(document).ready(function($){
      $('#modalContactTheAttorney').on('shown.bs.modal', function(){
        hbspt.forms.create({
          css: '',
          portalId: '1841598',
          formId: '7bcdf58f-fa90-4d82-a371-f932efbd3220',
          target: '.inner-modal-contact-the-attorney',
          onFormReady: function($form) {
            setTimeout(function () {
              var $emailAttorney = $form.find("[name='email_to_attorney']");
              console.log($emailAttorney.prop('nodeName'));
              $emailAttorney.attr('value', attorneyData.attorneyEmail);
            }, 1);

          }
        });
      });
    });
  </script>

</div>
<?php get_footer(); ?>
