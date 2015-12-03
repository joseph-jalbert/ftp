<?php
/**
 * The header for landing pages.
 *
 * @package ForThePeople
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php the_title(); ?></title>
<link rel="stylesheet" href="/wp-content/themes/forthepeople/assets/landing/css/style-2.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<?php wp_head(); ?>
</head>
<body class="landing">
<?php get_template_part( 'template-parts/google-tag-manager' ); ?>