<?php

function add_post_types_nav_menu() {
	add_meta_box( 'post_types_meta_box', __( 'Local Blog Archive' ), 'render_add_post_types_nav_menu', 'nav-menus', 'side', 'default' );
}

add_action( 'admin_init', 'add_post_types_nav_menu' );

function render_add_post_types_nav_menu() {
	$taxonomy = 'office_location';
	$terms    = get_terms( $taxonomy, array('hide_empty' => false) );

	?>
	<div id="localblog-archive" class="localblogdiv">
		<ul class="localblog-tabs add-menu-item-tabs">
			<li class="tabs"><?php _e( 'Local Blog Archives' ); ?></li>
		</ul>
		<div class="tabs-panel tabs-panel-active">
			<ul class="categorychecklist form-no-clear">
				<?php $i = 0;
				foreach ( $terms as $term ) : $i ++; ?>
					<li>
						<label class="menu-item-title"><input type="checkbox" class="menu-item-checkbox"
						                                      name="menu-item[-<?php echo $i; ?>][menu-item-object-id]"
						                                      value="<?php echo $term->slug; ?>"> <?php echo $term->name; ?>
						</label>
						<input type="hidden" class="menu-item-title"
						       name="menu-item[-<?php echo $i; ?>][menu-item-title]"
						       value="<?php echo $term->slug; ?>">
						<input type="hidden" class="menu-item-url" name="menu-item[-<?php echo $i; ?>][menu-item-url]"
						       value="<?php echo get_term_link( $term, $taxonomy ); ?>">
						<input type="hidden" value="custom" name="menu-item[-<?php echo $i; ?>][menu-item-type]">
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

            <span class="add-to-menu">
                <input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu"
                       name="add-local-blog-menu-item" id="submit-localblog-archive">
                <span class="spinner"></span>
            </span>
		</p>
	</div>
	<?php
}