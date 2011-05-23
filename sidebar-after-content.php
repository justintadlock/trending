<?php
/**
 * After Content Sidebar Template
 *
 * Displays any widgets for the After Content dynamic sidebar if they are available.
 *
 * @package Trending
 * @subpackage Template
 */

if ( is_active_sidebar( 'after-content' ) ) : ?>

	<div id="sidebar-after-content" class="sidebar">

		<?php dynamic_sidebar( 'after-content' ); ?>

	</div><!-- #sidebar-after-content -->

<?php endif; ?>