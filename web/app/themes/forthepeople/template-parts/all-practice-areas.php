<?php
/**
 * @package ForThePeople
 */

if ($post_type)
{
    $post_type_data = get_post_type_object( $post_type );
	$post_type_name = $post_type_data->labels->singular_name;
}
?>

<p>The attorneys in our <?php echo $post_type_name; ?> office handle cases in the following areas of practice:</p>

<?php	
		
$currentpage = get_the_ID();
        
$practice_areas = get_posts("post_type=$post_type&exclude=$currentpage&meta_key=shorthand&orderby=meta_value&order=ASC&posts_per_page=-1");

if( $practice_areas ) : ?>
    <ul class="practice-areas two-col clearfix">
    <?php foreach( $practice_areas as $post ) : ?>
        <li><a href="<?php the_permalink(); ?>" title="Go to <?php the_title(); ?>"> <?php the_field('shorthand'); ?></a></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>