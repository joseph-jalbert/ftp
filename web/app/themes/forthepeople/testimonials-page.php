<?php
/**
 * Template Name: Testimonials Page
 */
get_header(); ?>
<div id="content" class="site-content container">
<div id="interior-page">
  <div class="row-fluid row-leading row-follow">
    <div id="col1" class="span8">
      <main id="main" class="site-main" role="main">
      	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
    			<div class="content-pane-border"></div>
				<h1 class="pagetitle"><?php the_field('page_title'); ?></h1>
        		<div class="subtitle"><?php the_field('sub_title'); ?></div>
       			<div class="heading-hr"></div>
        
        		<div class="socialmediawidget vertical offpage">
					<span class='st_plusone_hcount' displayText='Google +1'></span>
					<span class='st_facebook_hcount' displayText='Facebook'></span>
					<span class='st_twitter_hcount' displayText='Tweet'></span>
					<span class='st_email_hcount' displayText='Email'></span>
				</div>
        
				<?php the_content(); ?>
       				
				<?php while ( have_posts() ) : the_post(); ?>
        		<?php get_template_part( 'template-parts/main-testimonials' ); ?>
        		<?php endwhile; ?>
                
			</div>
		</article>
      </main>
    </div>
    <div id="col2" class="span4">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
