<?php
/**
 * The template used for displaying page content in business-litigation.php
 *
 * @package ForThePeople
 */
?>


<?php if(get_field('jumbotron')) { ?>
<?php the_field('jumbotron'); ?>
<?php } ?>

<?php if(get_field('business_attorneys')) { ?>
<?php the_field('business_attorneys'); ?>
<?php } ?>

<?php if(get_field('who_we_are')) { ?>
<?php the_field('who_we_are'); ?>
<?php } ?>

<?php if(get_field('business_cases_we_handle')) { ?>
<?php the_field('business_cases_we_handle'); ?>
<?php } ?>

<?php if(get_field('our_success_contact_us')) { ?>
<?php the_field('our_success_contact_us'); ?>
<?php } ?>