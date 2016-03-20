<?php
/**
 * The template used for displaying page content in our-attorneys.php
 *
 * @package ForThePeople
 */

$parents  = get_post_ancestors( $post->ID );
$id       = ( $parents ) ? $parents[ count( $parents ) - 1 ] : $post->ID;
$parent   = get_post( $id );
$location = $parent->post_name;


$transient_name = md5('attorneys-' . $id . $location);

$output = get_transient( $transient_name );

if ( ! $output ) :
	ob_start();


	?>


	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<div class="content-pane-border"></div>
			<h1 class="pagetitle"><?php the_field( 'page_title' ); ?></h1>
			<div class="subtitle"><?php the_field( 'sub_title' ); ?></div>
			<div class="heading-hr"></div>

			<?php the_content(); ?>

			<div id="pageFilter"></div>

			<div class="attoreyList-all row-fluid">


				<?php


				if ( $location == 'attorneys' ) {
					$args = array(
						'post_type'      => 'attorney',
						'posts_per_page' => - 1,
						'orderby'        => 'meta_value',
						'meta_key'       => 'last_name',
						'order'          => 'ASC',
					);
				} else {
					$args = array(
						'post_type'      => 'attorney',
						'posts_per_page' => - 1,
						'orderby'        => 'meta_value',
						'meta_key'       => 'last_name',
						'order'          => 'ASC',
						'tax_query'      => array(
							array(
								'taxonomy' => 'location',
								'field'    => 'slug',
								'terms'    => $location,
							),
						),
					);
				}

				$query = new WP_Query( $args );


				$by_letter = array();
				while ( $query->have_posts() ) {
					$query->the_post();
					global $post;
					$letter = substr( get_field( 'last_name' ), 0, 1 );
					if ( ! isset( $by_letter[ $letter ] ) ) {
						$by_letter[ $letter ] = array();
					}
					$by_letter[ $letter ][] = $post;
				}
				wp_reset_postdata();


				if ( ! empty( $by_letter ) ) {

					ksort( $by_letter );

					?>

					<div class="span4">

						<?php
						$break = (int) ceil( count( $by_letter ) / 3 );
						$n     = 0;
						foreach ( $by_letter as $letter => $posts ) {
							$n ++;
							?>
							<strong class="allcaps"><?php echo $letter; ?></strong>
							<ul class="unstyled">
								<?php
								if ( ! empty( $posts ) ) {
									foreach ( $posts as $post ) {
										setup_postdata( $post );
										echo '<li><a href="' . get_permalink() . '">' . get_field( 'last_name' ) . ', ' . get_field( 'first_name' ) . '</a></li>';
									}
								}
								?>
							</ul>
							<?php $break2 = $break * 2;
							if ( $n === $break || $n === $break2 ) {
								echo '</div><div class="span4">';
							}
						}
						?>
					</div>
					<?php
					wp_reset_postdata();
				}
				?>


			</div>
		</div>
	</article>
	<?php
	$output = ob_get_clean();
	set_transient( $transient_name, $output, MINUTE_IN_SECONDS * 10 );

endif;

echo $output;