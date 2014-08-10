<div class="wrap sportspress">
	<h2>
		<?php _e( 'SportsPress', 'sportspress' ); ?>
	</h2>
	<div class="sp-sitemap">
		<?php $hierarchy = sp_post_type_hierarchy(); ?>
		<?php if ( ! isset( $_GET['type'] ) && ! isset( $_GET['taxonomy'] ) ): ?>

			<?php // Overview ?>

			<ul id="sp-utility">
				<?php foreach ( $hierarchy as $post_type => $children ): $object = get_post_type_object( $post_type ); ?>
					<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'type' => $post_type ), 'admin.php' ) ) ); ?>"><?php echo $object->labels->name; ?></a></li>
				<?php endforeach; ?>
			</ul>

			<?php $taxonomies = sp_taxonomies(); ?>
			<ul id="sp-primary" class="col<?php echo sizeof( $taxonomies ); ?>">
				<li class="sp-home"><a class="button disabled"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
				<?php foreach ( $taxonomies as $taxonomy ): $object = get_taxonomy( $taxonomy ); ?>
					<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy ), 'admin.php' ) ) ); ?>"><?php echo $object->labels->name; ?></a>
						<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0, 'orderby' => 'slug' ) ); ?>
						<ul>
							<?php if ( sizeof( $terms ) > 0 ): ?>
								<?php foreach ( $terms as $term ): ?>
									<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>
									<li>
										<?php if ( ! $children && sizeof ( $object->object_type ) <= 1 ): ?>
											<?php if ( sizeof( $object->object_type ) ): foreach ( $object->object_type as $post_type ): ?>
												<a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $term->name; ?></a>
											<?php endforeach; endif; ?>
										<?php else: ?>
											<a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy, 'term' => $term->term_id ), 'admin.php' ) ) ); ?>"><?php echo $term->name; ?></a>
										<?php endif; ?>

										<?php if ( $children ): ?>
											<ul>
												<?php foreach ( $children as $child ): ?>
													<li>
														<?php if ( sizeof ( $object->object_type ) <= 1 ): ?>
															<?php if ( sizeof( $object->object_type ) ): foreach ( $object->object_type as $post_type ): ?>
																<a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo $child->name; ?></a>
															<?php endforeach; endif; ?>
														<?php else: ?>
															<a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy, 'term' => $child->term_id ), 'admin.php' ) ) ); ?>"><?php echo $child->name; ?></a>
														<?php endif; ?>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							<?php else: ?>
								<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type ), 'post-new.php' ) ) ); ?>"><?php echo $object->labels->add_new_item; ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>

		<?php elseif ( ! isset( $_GET['type'] ) ): $taxonomy = $_GET['taxonomy']; $taxonomy_object = get_taxonomy( $_GET['taxonomy'] ); ?>

			<?php if ( isset( $_GET['term'] ) ): $term = get_term( $_GET['term'], $taxonomy ); // Posts in term ?>

				<ul id="sp-utility">
					<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'taxonomy' => $taxonomy ), 'edit-tags.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->edit_item; ?></a></li>
				</ul>

				<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>

				<?php if ( $terms ): // Has children ?>

					<ul id="sp-primary" class="col<?php echo sizeof( $terms ) + sizeof ( $taxonomy_object->object_type ); ?>">
						<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
						<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy ), 'admin.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->name; ?></a></li>
						<li class="sp-home"><a class="button disabled"><?php echo $term->name; ?></a></li>
						<li><a class="button disabled"><?php _e( 'All', 'sportspress' ); ?></a>
							<ul>
								<?php if ( sizeof ( $taxonomy_object->object_type ) ): ?>
									<?php foreach ( $taxonomy_object->object_type as $post_type ): if ( array_key_exists( $post_type, $hierarchy ) ): $post_object = get_post_type_object( $post_type ); ?>
										<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?></a></li>
									<?php endif; endforeach; ?>
								<?php endif; ?>
							</ul>
						</li>
						<?php foreach ( $terms as $term ): ?>
							<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy, 'term' => $term->term_id ), 'admin.php' ) ) ); ?>"><?php echo $term->name; ?></a>
							<?php if ( sizeof ( $taxonomy_object->object_type ) ): ?>
								<ul>
									<?php foreach ( $taxonomy_object->object_type as $post_type ): if ( array_key_exists( $post_type, $hierarchy ) ): $post_object = get_post_type_object( $post_type ); ?>
										<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?></a></li>
									<?php endif; endforeach; ?>
								</ul>
							<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				
				<?php else: // No children ?>

					<?php
					$post_types = array();
					foreach ( $taxonomy_object->object_type as $post_type ):
						if ( array_key_exists( $post_type, $hierarchy ) ):
							$post_types[] = $post_type;
						endif;
					endforeach;
					?>

					<ul id="sp-primary" class="col<?php echo sizeof( $post_types ); ?>">
						<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
						<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy ), 'admin.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->name; ?></a></li>
						<?php if ( $term->parent ): $parent = get_term( $term->parent, $taxonomy ); ?>
							<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy, 'term' => $term->parent ), 'admin.php' ) ) ); ?>"><?php echo $parent->name; ?></a></li>
						<?php endif; ?>
						<li class="sp-home"><a class="button disabled"><?php echo $term->name; ?></a></li>
						<?php if ( sizeof ( $taxonomy_object->object_type ) ): ?>
							<ul>
								<?php foreach ( $post_types as $post_type ): $post_object = get_post_type_object( $post_type ); ?>
									<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</ul>

				<?php endif; ?>

			<?php else: // Taxonomy archive ?>

				<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0, 'orderby' => 'slug' ) ); ?>
				<ul id="sp-primary" class="col<?php echo sizeof( $terms ) + 1; ?>">
					<?php $object = get_taxonomy( $_GET['taxonomy'] ); ?>
					<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
					<li class="sp-home"><a class="button disabled"><?php echo $object->labels->name; ?></a></li>
					<?php if ( $terms ): ?>
						<?php foreach ( $terms as $term ): ?>
							<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy, 'term' => $term->term_id ), 'admin.php' ) ) ); ?>"><?php echo $term->name; ?></a>
								<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>
								<ul>

									<?php if ( $children ): // Has children ?>

										<?php if ( sizeof( $object->object_type ) <= 1 ): ?>

											<?php foreach ( $children as $child ): ?>
												<?php if ( sizeof ( $object->object_type ) ): ?>
													<?php foreach ( $object->object_type as $post_type ): if ( array_key_exists( $post_type, $hierarchy ) ): $post_object = get_post_type_object( $post_type ); ?>
														<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo $child->name; ?></a></li>
													<?php endif; endforeach; ?>
												<?php endif; ?>
											<?php endforeach; ?>

										<?php else: ?>

											<li>
												<ul>
													<?php if ( sizeof ( $taxonomy_object->object_type ) ): ?>
														<?php foreach ( $taxonomy_object->object_type as $post_type ): if ( array_key_exists( $post_type, $hierarchy ) ): $post_object = get_post_type_object( $post_type ); ?>
															<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?></a></li>
														<?php endif; endforeach; ?>
													<?php endif; ?>
												</ul>
											</li>

											<?php foreach ( $children as $child ): ?>
												<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'taxonomy' => $taxonomy, 'term' => $child->term_id ), 'admin.php' ) ) ); ?>"><?php echo $child->name; ?></a>
													<?php if ( sizeof ( $object->object_type ) ): ?>
														<ul>
															<?php foreach ( $object->object_type as $post_type ): if ( array_key_exists( $post_type, $hierarchy ) ): $post_object = get_post_type_object( $post_type ); ?>
																<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?></a></li>
															<?php endif; endforeach; ?>
														</ul>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>

										<?php endif; ?>

									<?php else: // No children ?>

										<?php if ( sizeof ( $object->object_type ) ): ?>
											<?php foreach ( $object->object_type as $post_type ): if ( array_key_exists( $post_type, $hierarchy ) ): $post_object = get_post_type_object( $post_type ); ?>
												<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?></a></li>
											<?php endif; endforeach; ?>
										<?php endif; ?>

									<?php endif; ?>

								</ul>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
					<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'taxonomy' => $taxonomy ), 'edit-tags.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->add_new_item; ?></a></li>
				</ul>

			<?php endif; ?>

		<?php elseif ( ! isset( $_GET['taxonomy'] ) ): $post_type = $_GET['type']; // Post type archive ?>

			<?php
			$post_object = get_post_type_object( $post_type );
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			?>

			<ul id="sp-utility">
				<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type ), 'edit.php' ) ) ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a></li>
				<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type ), 'post-new.php' ) ) ); ?>"><?php echo $post_object->labels->add_new_item; ?></a></li>
			</ul>

			<?php if ( sizeof( $taxonomies ) || sizeof( sp_array_value( $hierarchy, $post_type ) ) ): // Display taxonomies ?>
				<ul id="sp-primary" class="col<?php echo sizeof( $taxonomies ) + sizeof( sp_array_value( $hierarchy, $post_type ) ); ?>">
					<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
					<li class="sp-home"><a class="button disabled"><?php echo $post_object->labels->name; ?></a></li>
					<?php foreach ( sp_array_value( $hierarchy, $post_type ) as $secondary_post_type ): $secondary_post_object = get_post_type_object( $secondary_post_type );?>
						<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $secondary_post_type ), 'edit.php' ) ) ); ?>"><?php echo $secondary_post_object->labels->name; ?></a>
							<?php $posts = get_posts( array( 'posts_per_page' => -1, 'post_type' => $secondary_post_type ) ); ?>
							<?php if ( $posts ): ?>
								<ul>
									<?php foreach ( $posts as $post ): ?>
										<li><a class="button" href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>"><?php echo $post->post_title; ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
					<?php foreach ( $taxonomies as $taxonomy_object ): $taxonomy = $taxonomy_object->name; ?>
						<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'type' => $post_type, 'taxonomy' => $taxonomy_object->name ), 'admin.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->name; ?></a>
							<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0, 'orderby' => 'slug' ) ); ?>
							<?php if ( $terms ): ?>
								<ul>
									<?php foreach ( $terms as $term ): ?>
										<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $term->name; ?></a>
											<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>

											<?php if ( $children ): // Has children ?>

												<ul>
													<?php foreach ( $children as $child ): ?>
														<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo $child->name; ?></a></li>
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
				<ul id="sp-primary" class="col<?php echo sizeof( $posts ); ?>">
					<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
					<li class="sp-home"><a class="button disabled"><?php echo $post_object->labels->name; ?></a></li>
					<?php if ( $posts ): ?>
						<?php foreach ( $posts as $post ): ?>
							<li><a class="button button-primary" href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>"><?php echo $post->post_title; ?></a></li>
						<?php endforeach; ?>
					<?php else: ?>
						<li><a class="button disabled"><?php _e( 'No results found.', 'sportspress' ); ?></a></li>
					<?php endif; ?>
				</ul>

			<?php endif; ?>

		<?php else: $post_type = $_GET['type']; $taxonomy = $_GET['taxonomy']; // Filtered posts ?>

			<?php $post_object = get_post_type_object( $post_type ); $taxonomy_object = get_taxonomy( $_GET['taxonomy'] ); ?>

			<?php $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0, 'orderby' => 'slug' ) ); ?>

			<ul id="sp-primary" class="col<?php echo sizeof( $terms ) + 1; ?>">
				<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview' ), 'admin.php' ) ) ); ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a></li>
				<li class="sp-breadcrumb"><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-overview', 'type' => $post_type ), 'admin.php' ) ) ); ?>"><?php echo $post_object->labels->name; ?></a></li>
				<li class="sp-home"><a class="button disabled"><?php echo $taxonomy_object->labels->name; ?></a></li>

				<?php if ( $terms ): foreach ( $terms as $term ): ?>
					<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ) ); ?>"><?php echo $term->name; ?></a>
						<?php $children = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id, 'orderby' => 'slug' ) ); ?>

						<?php if ( $children ): // Has children ?>

							<ul>
								<?php foreach ( $children as $child ): ?>
									<li><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $child->slug ), 'edit.php' ) ) ); ?>"><?php echo $child->name; ?></a></li>
								<?php endforeach; ?>
							</ul>

						<?php endif; ?>

					</li>
				<?php endforeach; endif; ?>
				<li><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'taxonomy' => $taxonomy ), 'edit-tags.php' ) ) ); ?>"><?php echo $taxonomy_object->labels->add_new_item; ?></a></li>
			</ul>

		<?php endif; ?>
	</div>
	<p>
		<a href="http://wordpress.org/support/view/plugin-reviews/sportspress?rate=5#postform">
			<?php _e( 'Love SportsPress? Help spread the word by rating us 5★ on WordPress.org', 'sportspress' ); ?>
		</a>
	</p>
</div>