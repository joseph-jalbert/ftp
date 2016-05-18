<?php

/**

 * The sidebar containing the main widget area.

 *

 * @package ForThePeople

 */

?>



<div class="widget-area" role="complementary">
	<a name="free-immediate-consultation"></a>
	<?php if ( is_active_sidebar( 'sidebar_contact_form' ) ) {

		dynamic_sidebar( 'sidebar_contact_form' );

	} else {

		dynamic_sidebar( 'sidebar-1' );

	} ?>

</div><!-- #col2 -->

