<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package ForThePeople
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>><head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/favicon.png">
<link rel="apple-touch-startup-image" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/startup.png">
<link rel="apple-touch-startup-image" sizes="640x1096" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/startup@2x.png">
<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/apple-touch-icon-144x144.png">
	<title><?php wp_title('-'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<script type="text/javascript" src="//use.typekit.net/zig7enb.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php wp_head(); ?>
<!--[if lt IE 9]><script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js"></script><![endif]-->
</head>

<?php
global $post;
$post_type = get_post_type($post);
if ($post_type)  {

	if ( Local_News::POST_TYPE === $post_type ) {

		$terms = wp_get_post_terms( get_the_ID(), Location_Taxonomy::LOCATION_TAXONOMY );
		if ( is_array($terms) && isset( $terms[0]->slug )) {

				$menuslug = $terms[0]->slug;


		}


	} else {
		$post_type_data = get_post_type_object( $post_type );
		$post_type_slug = $post_type_data->rewrite['slug'];
		$post_type_name = $post_type_data->labels->singular_name;
		$menuslug       = $post_type_slug;
	}
}


if ( is_page_template( 'office-location.php' ) ) {
global $post;
$post_slug=$post->post_name;
$menuslug = $post_slug;
}

if( is_page_template( 'practice-area.php' )  && ($post->post_parent != 0) || is_page_template('our-attorneys-inner.php')) {
global $post;
$parents = get_post_ancestors( $post->ID );
$id = ($parents) ? $parents[count($parents)-1]: $post->ID;
$parent = get_post( $id );
$parentslug = $parent->post_name;
$menuslug = $parentslug;
}

if ( $post->post_parent > 0 ) {

	$parent = $post->post_parent;
	if ( get_page_template_slug($parent) === 'office-location.php') {
		$parent = get_post($parent);
		$menuslug = $parent->post_name;
	}

}

if(is_page_template('all-class-actions.php') || is_singular('classactionlawyers') || is_singular('attorney') || is_page('poisoning-symptoms')) {
$menuslug = 'main-navigation';
}

if ( $post->post_name === 'business-litigation-lawyers' || is_page_template( 'business-litigation.php' ) || is_child( '6110' ) || $post_type === BTG_News::POST_TYPE || BTG_News::get_query_var_value() ) {
$menuslug = 'business-litigation';
}

if(is_page_template('securities-litigation.php') || is_child('6162')) {
$menuslug = 'securities-litigation';
}

if (empty($menuslug) || is_page('orlando')) {
$menuslug = 'main-navigation';
}
?>

<body <?php body_class(); ?>>
<?php get_template_part( 'template-parts/google-tag-manager' ); ?>
<!--[if lte IE 8]> <div id="ie"> <![endif]-->
	<div class="siteNav-wrap sticky-canvas-nav">
        <div class="btn-navbar">
    <button class="btn canvas-nav-toggle"><i class="icon-reorder"></i> Menu</button>
    <a class="btn btn-warning pull-right nav-clicktocall" onclick="trackEventGA('Click to Call', 'Call', 'Header', 550);" href="tel:<?php if(is_single('matt-morgan')) { ?>4072443211<?php } else { ?>8776674265<?php } ?>"><i class="icon-phone"></i> Click to Call</a>
    <a href="<?php echo esc_url( location_aware_contact_us_page_url() ); ?>" class="btn btn-warning pull-right nav-contactus"><i class="icon-legal"></i> Contact Us</a>
	</div>
	</div>

<div id="page-wrap" class="hfeed site">
	<span class="nav-overlay"></span>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'forthepeople' ); ?></a>

	<header id="masthead" class="header-wrap" role="banner">
			<div class="container header-container clearfix">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="logo<?php if (!is_page()) { echo ' tt'; } ?>" <?php if (is_singular()) { echo 'data-toggle="tooltip" data-placement="left" data-original-title="Site Home"'; } ?>><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-header.png" alt="Morgan & Morgan - ForThePeople.com" height="137" width="565" border="0" /></a>

<div class="advert-txt">this is an advertisement</div>

        <div class="phone pull-right hidden-phone">
        	<div class="actions">
                 <div id="head-search" class="clearfix">


					<div class="search-container clearfix">
						<form class="searchbox" action="/" method="GET">
							<input type="search" placeholder="Search..." name="s" class="searchbox-input" onkeyup="buttonUp();" required>
							<input type="submit" class="searchbox-submit" value="GO">
							<span class="searchbox-icon">&nbsp;</span>
						</form>
					</div>

                 </div>
        	</div>
            <div class="cta">24 hours / 7 days a week. Se habla español.</div>
            <span class="number tk-minion-pro">877.667.4265</span>
        </div>
    </div>
	<div class="mobile-only">Se habla español</div>
</header><!-- #masthead -->


	<nav id="site-nav" class="siteNav-wrap clearfix" role="navigation">
		<div class="btn-navbar">
    			<button class="btn canvas-nav-toggle"><i class="icon-reorder"></i> Menu</button>
    			<a class="btn btn-warning pull-right nav-clicktocall" onclick="trackEventGA('Click to Call', 'Call', 'Header', 550);" href="tel:<?php if(is_single('matt-morgan')) { ?>4072443211<?php } else { ?>8776674265<?php } ?>"><i class="icon-phone"></i> Click to Call</a>
    			<a href="<?php echo esc_url( location_aware_contact_us_page_url() ); ?>" class="btn btn-warning pull-right nav-contactus"><i class="icon-legal"></i> Contact Us</a>
		</div>
		<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'forthepeople' ); ?></button>
<div class="container siteNav-container nav-collapse collapse">
		<!--[if lt IE 10]>
 		 <div id="iefallback">
        <![endif]-->

<?php
    $defaults = array(
        'menu'  => $menuslug,
        'menu_id'      => 'topNav',
        'walker'          => new My_Sub_Menu(),
        'container'       =>  false
    );
    wp_nav_menu( $defaults );

?>
		<!--[if lt IE 10]>
 		 </div>
        <![endif]-->

</div>

		</nav><!-- #site-navigation -->



<?php if ( is_page_template( 'office-location.php' ) ) { ?>
	<script>
	jQuery("#topNav > li.marker").prepend("<div class='nav-label'><?php echo the_title(); ?></div>");
	</script>
<?php } ?>

<?php if(is_page_template('practice-area.php') && ($post->post_parent != 0) && !is_page_template('practice-area-inner.php') || is_page_template('our-attorneys-inner.php')) {
	$parent = get_post($post->post_parent);
	$parent_title = get_the_title($parent);
	$grandparent = $parent->post_parent;
	$grandparent_title = get_the_title($grandparent);
		if ($grandparent == is_page('0')) {
	$marker = $grandparent_title;
	} elseif ($post->post_parent ==is_page('0')) {
    $marker = $parent_title;
    } ?>
	<script>
	jQuery("#topNav > li.marker").prepend("<div class='nav-label'><?php echo $marker; ?></div>");
	</script>
<?php } ?>

<?php if(is_page_template('all-class-actions.php') || is_singular('classactionlawyers') || is_page('carbon-monoxide-lawyers')) { ?>
	<script>
	jQuery("#topNav > li.marker").prepend("<div class='nav-label'>National</div>");
	</script>
<?php } ?>
 
<?php if(!is_front_page() &&
         !is_page_template(array(
                        'all-class-actions.php',
                        'class-action-cats.php', 
                        'business-litigation.php',
                        'securities-litigation.php',
                        'all-office-locations.php',
                        'contact-page.php',
                        'diabetes-page.php',
                        'office-location.php',
                        'mesothelioma.php',
                        'empty-page.php',
                        'securities-contact-page.php',
                        'tampa-alternate.php',
                        'empty-page-title.php')) && 
          !is_singular('attorney') &&
          !in_category( 'featured-news', $_post ) &&
          !is_page('featured-news')) {     
    get_template_part( 'template-parts/breadcrumbs' );
} ?>
