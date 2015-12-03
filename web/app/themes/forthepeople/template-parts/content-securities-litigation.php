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

<?php if(get_field('standing_up')) { ?>
<?php the_field('standing_up'); ?>
<?php } ?>

<?php if(get_field('protecting')) { ?>
<?php the_field('protecting'); ?>
<?php } ?>

<?php if(get_field('actions')) { ?>
<?php the_field('actions'); ?>
<?php } ?>

<?php if(get_field('promo')) { ?>
<?php the_field('promo'); ?>
<?php } ?>

<?php if(get_field('services')) { ?>
<?php the_field('services'); ?>
<?php } ?>