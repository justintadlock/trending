<?php
/**
 * Template Name: Archives
 *
 * A custom page template for displaying blog archives.
 *
 * @package Trending
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // trending_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // trending_open_content ?>

		<div class="hfeed">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php do_atomic( 'before_entry' ); // trending_before_entry ?>

					<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

						<?php do_atomic( 'open_entry' ); // trending_open_entry ?>

						<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>

						<div class="entry-content">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', hybrid_get_textdomain() ) ); ?>

							<h2><?php _e( 'Archives by category', hybrid_get_textdomain() ); ?></h2>

							<ul class="xoxo category-archives">
								<?php wp_list_categories( array( 'feed' => __( 'RSS', hybrid_get_textdomain() ), 'show_count' => true, 'use_desc_for_title' => false, 'title_li' => false ) ); ?>
							</ul><!-- .xoxo .category-archives -->

							<h2><?php _e( 'Archives by month', hybrid_get_textdomain() ); ?></h2>

							<ul class="xoxo monthly-archives">
								<?php wp_get_archives( array( 'show_post_count' => true, 'type' => 'monthly' ) ); ?>
							</ul><!-- .xoxo .monthly-archives -->

							<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
						</div><!-- .entry-content -->

						<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[entry-edit-link]</div>' ); ?>

						<?php do_atomic( 'close_entry' ); // trending_close_entry ?>

					</div><!-- .hentry -->

					<?php do_atomic( 'after_entry' ); // trending_after_entry ?>

					<?php get_sidebar( 'after-singular' ); // Loads the sidebar-after-singular.php template. ?>

					<?php do_atomic( 'after_singular' ); // trending_after_singular ?>

					<?php comments_template( '/comments.php', true ); // Loads the comments.php template. ?>

				<?php endwhile; ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // trending_close_content ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // trending_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>