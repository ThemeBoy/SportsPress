<?php
/*
Plugin Name: SportsPress Overview
Plugin URI: http://themeboy.com/
Description: Display a visual overview of SportsPress data.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.8.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Overview' ) ) :

/**
 * Main SportsPress Overview Class
 *
 * @class SportsPress_Overview
 * @version	1.8.3
 */
class SportsPress_Overview {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_OVERVIEW_VERSION' ) )
			define( 'SP_OVERVIEW_VERSION', '1.8.3' );

		if ( !defined( 'SP_OVERVIEW_URL' ) )
			define( 'SP_OVERVIEW_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_OVERVIEW_DIR' ) )
			define( 'SP_OVERVIEW_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add menu item
	 */
	public function admin_menu() {
		add_submenu_page( 'sportspress', __( 'Overview', 'sportspress' ), __( 'Overview', 'sportspress' ), 'manage_sportspress', 'sportspress-overview', array( $this, 'overview_page' ) );
	}

	/**
	 * Init the overview page
	 */
	public function overview_page() {
		?>
		<div class="wrap sportspress sportspress-overview-wrap">
			<h2>
				<?php _e( 'Overview', 'sportspress' ); ?>
			</h2>
			<div class="sp-sitemap">

				<?php $primary_post_types = sp_primary_post_types(); $hierarchy = sp_post_type_hierarchy(); ?>

				<?php if ( ! isset( $_GET['type'] ) && ! isset( $_GET['taxonomy'] ) ): // Overview ?>

					<ul class="sp-utility">
						<?php foreach ( $primary_post_types as $post_type ): if ( post_type_exists( $post_type ) ): $object = get_post_type_object( $post_type ); ?>
							<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'type' => $post_type ), 'admin.php' ) ) ); ?>"><?php echo $object->labels->name; ?></a></li>
						<?php endif; endforeach; ?>
					</ul>

					<?php $taxonomies = sp_taxonomies(); ?>
					<ul class="sp-primary col<?php echo sizeof( $taxonomies ); ?>">
						<li class="sp-home"><a class="button disabled"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
						<?php foreach ( $taxonomies as $taxonomy ): $object = get_taxonomy( $taxonomy ); $post_types = apply_filters( 'sportspress_sitemap_taxonomy_post_types', $object->object_type, $taxonomy ); ?>
							<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy ), 'admin.php' ) ) ); ?>"><?php echo $object->labels->name; ?></a>
								<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0, 'orderby' => 'slug' ) ); ?>
								<ul>
									<?php if ( sizeof( $terms ) > 0 ): ?>
										<?php foreach ( $terms as $term ): ?>
											<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>
											<li>
												<?php if ( ! $children && sizeof ( $post_types ) <= 1 ): ?>
													<?php if ( sizeof( $post_types ) ): foreach ( $post_types as $post_type ): ?>
														<a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $term->name ); ?><span class="dashicons dashicons-list-view wp-ui-text-notification"></span></a>
													<?php endforeach; endif; ?>
												<?php else: ?>
													<a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy, 'term' => $term->term_id ), 'admin.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $term->name ); ?></a>
												<?php endif; ?>

												<?php if ( $children ): ?>
													<ul>
														<?php foreach ( $children as $child ): ?>
															<li>
																<?php if ( sizeof( $post_types ) <= 1 ): ?>
																	<?php if ( sizeof( $post_types ) ): foreach ( $post_types as $post_type ): ?>
																		<a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $child->name ); ?><span class="dashicons dashicons-list-view wp-ui-text-highlight"></span></a>
																	<?php endforeach; endif; ?>
																<?php else: ?>
																	<a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy, 'term' => $child->term_id ), 'admin.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $child->name ); ?></a>
																<?php endif; ?>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									<?php else: ?>
										<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'taxonomy' => $taxonomy ), 'edit-tags.php' ) ) ); ?>"><?php echo $object->labels->add_new_item; ?></a></li>
									<?php endif; ?>
								</ul>
							</li>
						<?php endforeach; ?>
					</ul>

				<?php elseif ( ! isset( $_GET['type'] ) ): $taxonomy = $_GET['taxonomy']; $taxonomy_object = get_taxonomy( $taxonomy ); ?>
						
					<?php $post_types = apply_filters( 'sportspress_sitemap_taxonomy_post_types', $taxonomy_object->object_type, $taxonomy ); ?>

					<?php if ( isset( $_GET['term'] ) ): $term = get_term( $_GET['term'], $taxonomy ); // Posts in term ?>

						<ul class="sp-utility">
							<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'taxonomy' => $taxonomy ), 'edit-tags.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->edit_item; ?></a></li>
						</ul>

						<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>

						<?php if ( $terms ): // Has children ?>

							<ul class="sp-primary col<?php echo sizeof( $terms ) + 1; ?>">
								<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
								<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy ), 'admin.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->name; ?></a></li>
								<li class="sp-home"><a class="button disabled"><?php echo wp_strip_all_tags( $term->name ); ?></a></li>
								<li><a class="button disabled"><?php _e( 'All', 'sportspress' ); ?></a>
									<ul>
										<?php if ( sizeof ( $post_types ) ): ?>
											<?php foreach ( $post_types as $post_type ): if ( post_type_exists( $post_type ) ): $post_object = get_post_type_object( $post_type ); ?>
												<li><a class="button button-primary action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?><span class="dashicons dashicons-list-view"></span></a></li>
											<?php endif; endforeach; ?>
										<?php endif; ?>
									</ul>
								</li>
								<?php foreach ( $terms as $term ): ?>
									<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy, 'term' => $term->term_id ), 'admin.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $term->name ); ?></a>
										<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>

										<?php if ( $children ): // Has children ?>

											<ul>
												<?php foreach ( $children as $child ): ?>
													<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $child->name ); ?><span class="dashicons dashicons-list-view wp-ui-text-notification"></span></a>

														<?php if ( sizeof ( $post_types ) ): // Has associated post types ?>
															<ul>
																<?php foreach ( $post_types as $post_type ): if ( post_type_exists( $post_type ) ): $post_object = get_post_type_object( $post_type ); ?>
																	<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?><span class="dashicons dashicons-list-view wp-ui-text-highlight"></span></a></li>
																<?php endif; endforeach; ?>
															</ul>
														<?php endif; ?>

													</li>
												<?php endforeach; ?>
											</ul>

										<?php elseif ( sizeof ( $post_types ) ): // Has associated post types ?>

											<ul>
												<?php foreach ( $post_types as $post_type ): if ( post_type_exists( $post_type ) ): $post_object = get_post_type_object( $post_type ); ?>
													<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?><span class="dashicons dashicons-list-view wp-ui-text-notification"></span></a></li>
												<?php endif; endforeach; ?>
											</ul>

										<?php endif; ?>

									</li>
								<?php endforeach; ?>
							</ul>
						
						<?php else: // No children ?>

							<ul class="sp-primary col<?php echo sizeof( $post_types ); ?>">
								<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
								<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy ), 'admin.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->name; ?></a></li>
								<?php if ( $term->parent ): $parent = get_term( $term->parent, $taxonomy ); ?>
									<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy, 'term' => $term->parent ), 'admin.php' ) ) ); ?>"><?php echo $parent->name; ?></a></li>
								<?php endif; ?>
								<li class="sp-home"><a class="button disabled"><?php echo wp_strip_all_tags( $term->name ); ?></a></li>
								<?php if ( sizeof ( $taxonomy_object->object_type ) ): ?>
									<ul>
										<?php foreach ( $post_types as $post_type ): if ( post_type_exists( $post_type ) ): $post_object = get_post_type_object( $post_type ); ?>
											<li><a class="button button-primary action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?><span class="dashicons dashicons-list-view"></span></a></li>
										<?php endif; endforeach; ?>
									</ul>
								<?php endif; ?>
							</ul>

						<?php endif; ?>

					<?php else: // Taxonomy archive ?>

						<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0, 'orderby' => 'slug' ) ); ?>
						<ul class="sp-primary col<?php echo sizeof( $terms ) + 1; ?>">
							<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
							<li class="sp-home"><a class="button disabled"><?php echo $taxonomy_object->labels->name; ?></a></li>
							<?php if ( $terms ): ?>
								<?php foreach ( $terms as $term ): ?>
									<li>
										<?php if ( sizeof( $post_types ) <= 1 ): $post_type = reset( $post_types ); ?>
											<a class="button button-primary action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $term->name ); ?><span class="dashicons dashicons-list-view"></span></a>
										<?php else: ?>
											<a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy, 'term' => $term->term_id ), 'admin.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $term->name ); ?></a>
										<?php endif; ?>
										<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>
										<ul>

											<?php if ( $children ): // Has children ?>

												<?php if ( sizeof( $post_types ) <= 1 ): ?>

													<?php foreach ( $children as $child ): ?>
														<?php if ( sizeof ( $post_types ) ): ?>
															<?php foreach ( $post_types as $post_type ): if ( post_type_exists( $post_type ) ): $post_object = get_post_type_object( $post_type ); ?>
																<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $child->name ); ?><span class="dashicons dashicons-list-view wp-ui-text-notification"></span></a></li>
															<?php endif; endforeach; ?>
														<?php endif; ?>
													<?php endforeach; ?>

												<?php else: ?>

													<?php if ( sizeof ( $post_types ) ): ?>
														<?php foreach ( $post_types as $post_type ): if ( post_type_exists( $post_type ) ): $post_object = get_post_type_object( $post_type ); ?>
															<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?><span class="dashicons dashicons-list-view wp-ui-text-notification"></span></a></li>
														<?php endif; endforeach; ?>
														<li></li>
														<li></li>
													<?php endif; ?>

													<?php foreach ( $children as $child ): ?>
														<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'taxonomy' => $taxonomy, 'term' => $child->term_id ), 'admin.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $child->name ); ?></a>
															<?php if ( sizeof ( $post_types ) ): ?>
																<ul>
																	<?php foreach ( $post_types as $post_type ): if ( post_type_exists( $post_type ) ): $post_object = get_post_type_object( $post_type ); ?>
																		<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?><span class="dashicons dashicons-list-view wp-ui-text-highlight"></span></a></li>
																	<?php endif; endforeach; ?>
																</ul>
															<?php endif; ?>
														</li>
													<?php endforeach; ?>

												<?php endif; ?>

											<?php else: // No children ?>

												<?php if ( sizeof ( $post_types ) > 1 ): ?>
													<?php foreach ( $post_types as $post_type ): if ( post_type_exists( $post_type ) ): $post_object = get_post_type_object( $post_type ); ?>
														<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?><span class="dashicons dashicons-list-view wp-ui-text-notification"></span></a></li>
													<?php endif; endforeach; ?>
												<?php endif; ?>

											<?php endif; ?>

										</ul>
									</li>
								<?php endforeach; ?>
							<?php endif; ?>
							<li><a class="button button-primary action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'taxonomy' => $taxonomy ), 'edit-tags.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->add_new_item; ?><span class="dashicons dashicons-plus"></span></a></li>
						</ul>

					<?php endif; ?>

				<?php elseif ( ! isset( $_GET['taxonomy'] ) ): $post_type = $_GET['type']; // Post type archive ?>

					<?php
					$post_object = get_post_type_object( $post_type );
					$taxonomies = get_object_taxonomies( $post_type, 'objects' );
					?>

					<ul class="sp-utility">
						<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type ), 'post-new.php' ) ) ); ?>"><?php echo $post_object->labels->add_new_item; ?></a></li>
						<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type ), 'edit.php' ) ) ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a></li>
					</ul>

					<?php if ( sizeof( $taxonomies ) || sizeof( sp_array_value( $hierarchy, $post_type ) ) ): // Display taxonomies ?>
						<ul class="sp-primary col<?php echo sizeof( $taxonomies ) + sizeof( sp_array_value( $hierarchy, $post_type ) ); ?>">
							<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
							<li class="sp-home"><a class="button disabled"><?php echo $post_object->labels->name; ?></a></li>
							<?php foreach ( sp_array_value( $hierarchy, $post_type ) as $secondary_post_type ): if ( post_type_exists( $secondary_post_type ) ): $secondary_post_object = get_post_type_object( $secondary_post_type ); ?>
								<li><a class="button button-primary action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $secondary_post_type ), 'edit.php' ) ) ); ?>"><?php echo $secondary_post_object->labels->name; ?><span class="dashicons dashicons-list-view"></span></a>
									<?php $posts = get_posts( array( 'posts_per_page' => -1, 'post_type' => $secondary_post_type ) ); ?>
									<?php if ( $posts ): ?>
										<ul>
											<?php foreach ( $posts as $post ): ?>
												<li><a class="button action" href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>"><?php echo wp_strip_all_tags( $post->post_title ); ?><span class="dashicons dashicons-edit wp-ui-text-notification"></span></a></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</li>
							<?php endif; endforeach; ?>
							<?php foreach ( $taxonomies as $taxonomy_object ): $taxonomy = $taxonomy_object->name; ?>
								<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'type' => $post_type, 'taxonomy' => $taxonomy_object->name ), 'admin.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->name; ?></a>
									<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0, 'orderby' => 'slug' ) ); ?>
									<?php if ( $terms ): ?>
										<ul>
											<?php foreach ( $terms as $term ): ?>
												<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $term->name ); ?><span class="dashicons dashicons-list-view wp-ui-text-notification"></span></a>
													<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>

													<?php if ( $children ): // Has children ?>

														<ul>
															<?php foreach ( $children as $child ): ?>
																<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $child->name ); ?><span class="dashicons dashicons-list-view wp-ui-text-highlight"></span></a></li>
															<?php endforeach; ?>
														</ul>

													<?php endif; ?>

												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>

					<?php else: // Display posts ?>

						<?php $posts = get_posts( array( 'posts_per_page' => -1, 'post_type' => $post_type ) ); ?>
						<ul class="sp-primary col<?php echo sizeof( $posts ); ?>">
							<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
							<li class="sp-home"><a class="button disabled"><?php echo $post_object->labels->name; ?></a></li>
							<?php if ( $posts ): ?>
								<?php foreach ( $posts as $post ): ?>
									<li><a class="button button-primary action" href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>"><?php echo wp_strip_all_tags( $post->post_title ); ?><span class="dashicons dashicons-edit"></span></a></li>
								<?php endforeach; ?>
							<?php else: ?>
								<li><a class="button disabled"><?php _e( 'No results found.', 'sportspress' ); ?></a></li>
							<?php endif; ?>
						</ul>

					<?php endif; ?>

				<?php else: $post_type = $_GET['type']; $taxonomy = $_GET['taxonomy']; // Filtered posts ?>

					<?php if ( post_type_exists( $post_type ) ): ?>
					
						<?php $post_object = get_post_type_object( $post_type ); $taxonomy_object = get_taxonomy( $_GET['taxonomy'] ); ?>

						<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0, 'orderby' => 'slug' ) ); ?>

						<ul class="sp-primary col<?php echo sizeof( $terms ) + 1; ?>">
							<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
							<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress-overview', 'type' => $post_type ), 'admin.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?></a></li>
							<li class="sp-home"><a class="button disabled"><?php echo $taxonomy_object->labels->name; ?></a></li>

							<?php if ( $terms ): foreach ( $terms as $term ): ?>
								<li><a class="button button-primary action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $term->name ); ?><span class="dashicons dashicons-list-view"></span></a>
									<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>

									<?php if ( $children ): // Has children ?>

										<ul>
											<?php foreach ( $children as $child ): ?>
												<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo wp_strip_all_tags( $child->name ); ?><span class="dashicons dashicons-list-view wp-ui-text-notification"></span></a>
													<?php $grandchildren = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $child->term_id, 'orderby' => 'slug' ) ); ?>

													<?php if ( $grandchildren ): // Has grandchildren ?>
														<ul>
															<?php foreach ( $grandchildren as $grandchild ): ?>
																<li><a class="button action" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $grandchild->slug ), 'edit.php' ) ) ); ?>"><?php echo $grandchild->name; ?><span class="dashicons dashicons-list-view wp-ui-text-highlight"></span></a></li>
															<?php endforeach; ?>
														</ul>
													<?php endif; ?>

												</li>
											<?php endforeach; ?>
										</ul>

									<?php endif; ?>

								</li>
							<?php endforeach; endif; ?>
							<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'taxonomy' => $taxonomy ), 'edit-tags.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->add_new_item; ?></a></li>
						</ul>

					<?php endif; ?>

				<?php endif; ?>
			</div>
			<?php do_action( 'sportspress_overview_page' ); ?>
		</div>
		<?php
	}
}

endif;

new SportsPress_Overview();
