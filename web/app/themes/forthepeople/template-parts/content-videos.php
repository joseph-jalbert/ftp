<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package ForThePeople
 */


?>

	<article class="video-outer-wrap" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<div class="content-pane-border"></div>
			<h1 class="pagetitle"><?php the_field( 'page_title' ); ?></h1>
			<div class="subtitle"><?php the_field( 'sub_title' ); ?></div>

			<div class="heading-hr"></div>
			<?php
			$youtube_channel_link = Videos_Settings::get( 'youtube_channel_link', get_the_ID() );
			if ( $youtube_channel_link ) :
				$youtube_channel_link_title = Videos_Settings::get( 'youtube_channel_link_title', get_the_ID() );
				if ( ! $youtube_channel_link_title ) :
					$youtube_channel_link_title = 'YouTube Channel';
				endif;

				?><p class="font-large text-center"><a
					href="<?php echo esc_url( $youtube_channel_link ); ?>"><?php esc_html_e( $youtube_channel_link_title ); ?></a>
				</p><?php
			endif;
			?>
			<div class="socialmediawidget vertical offpage">
				<span class='st_plusone_hcount' displayText='Google +1'></span>
				<span class='st_facebook_hcount' displayText='Facebook'></span>
				<span class='st_twitter_hcount' displayText='Tweet'></span>
				<span class='st_email_hcount' displayText='Email'></span>
			</div>


			<?php

			$videos  = Videos_Settings::get_videos( get_the_ID() );
			$counter = 0;
			if ( isset( $videos ) && is_array( $videos ) ) :
				foreach ( $videos as $video ) :
					$counter ++;
					$video_title       = $video['title'];
					$video_thumbnail   = $video['thumbnail']['url'];
					$video_url         = YouTube_Helper::get_youtube_url( $video['youtube_id'] );
					$video_description = $video['description'];
					$video_upload_date = $video['upload_date'];
					$video_transcript  = $video['transcript'];
					$video_id          = $video['youtube_id'];
					if ( 1 === $counter ) :

						?>


						<div id="yt-player" data-video-id="<?php esc_attr_e( $video_id ); ?>"></div>

						


						<?php

					endif;


					?>

					<ul class="video-playlist unstyled no-margin-no-pad">


						<li data-video-id="<?php echo esc_attr( $video_id ); ?>" class="video-wrapper"
						    itemtype="http://schema.org/VideoObject"
						    itemscope="" itemprop="video">
							<div class="row-fluid">
								<div class="span4"><img itemprop="thumbnailUrl"
								                        src="<?php echo esc_url( $video_thumbnail ); ?>"
								                        class="thumbnail videoplaylist"></div>
								<div class="span8 meta">
									<button itemprop="name"
									        class="videoplaylist btn btn-link"><?php esc_html_e( $video_title ); ?>
									</button>

									<p itemprop="description"><?php echo wp_kses_post( $video_description ); ?></p>
								</div>
							</div>
							<meta content="<?php echo esc_url( $video_url ); ?>" itemprop="contentURL">
							<meta content="<?php echo esc_url( $video_upload_date ); ?>" itemprop="uploadDate">
							<meta content="<?php echo esc_url( $video_transcript ); ?>" itemprop="transcript">
						</li>
					</ul>
				<?php endforeach;

			endif; ?>
		</div>

	</article>