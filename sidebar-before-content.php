<?php
/**
 * Before Content Sidebar Template
 *
 * Displays any widgets for the Before Content dynamic sidebar if they are available.
 *
 * @package Trending
 * @subpackage Template
 */

if ( is_active_sidebar( 'before-content' ) ) : ?>

	<div id="sidebar-before-content" class="sidebar">

		<?php dynamic_sidebar( 'before-content' ); ?>

	</div><!-- #sidebar-before-content -->

<?php endif; ?>