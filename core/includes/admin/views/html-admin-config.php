<div class="wrap sportspress sp-config-wrap">
	<h2>
		<?php _e( 'Configure', 'sportspress' ); ?>
	</h2>
	<table class="form-table">
		<tbody>
			<?php
			$args = array(
				'post_type' => 'sp_outcome',
				'numberposts' => -1,
				'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC'
			);
			$data = get_posts( $args );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Event Outcomes', 'sportspress' ) ?>
					<p class="description"><?php _e( 'Used for events.', 'sportspress' ); ?></p>
				</th>
			    <td class="forminp">
					<table class="widefat sp-admin-config-table">
						<thead>
							<tr>
								<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Variable', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Abbreviation', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Description', 'sportspress' ); ?></th>
								<th scope="col" class="edit"></th>
							</tr>
						</thead>
						<?php if ( $data ): $i = 0; foreach ( $data as $row ): ?>
							<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
								<td class="row-title"><?php echo $row->post_title; ?></td>
								<td><?php echo $row->post_name; ?></td>
								<td><?php echo sp_get_post_abbreviation( $row->ID ); ?></td>
								<td><p class="description"><?php echo $row->post_excerpt; ?></p></td>
								<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
							</tr>
						<?php $i++; endforeach; else: ?>
							<tr class="alternate">
								<td colspan="5"><?php _e( 'No results found.', 'sportspress' ); ?></td>
							</tr>
						<?php endif; ?>
					</table>
					<div class="tablenav bottom">
						<div class="alignleft actions">
							<a class="button button-primary" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_outcome' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
							<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_outcome' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						</div>
						<br class="clear">
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="form-table">
		<tbody>
			<?php
			$selection = get_option( 'sportspress_primary_result', 0 );

			$args = array(
				'post_type' => 'sp_result',
				'numberposts' => -1,
				'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC'
			);
			$data = get_posts( $args );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Team Results', 'sportspress' ) ?>
					<p class="description"><?php _e( 'Used for events.', 'sportspress' ); ?></p>
				</th>
			    <td class="forminp">
					<legend class="screen-reader-text"><span><?php _e( 'Team Results', 'sportspress' ) ?></span></legend>
					<form>
						<?php wp_nonce_field( 'sp-save-primary-result', 'sp-config-nonce', false ); ?>
						<table class="widefat sp-admin-config-table">
							<thead>
								<tr>
									<th class="radio" scope="col"><?php _e( 'Primary', 'sportspress' ); ?></th>
									<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
									<th scope="col"><?php _e( 'Variables', 'sportspress' ); ?></th>
									<th scope="col"><?php _e( 'Description', 'sportspress' ); ?></th>
									<th scope="col" class="edit"></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th class="radio"><input type="radio" class="sp-primary-result-option" id="sportspress_primary_result_0" name="sportspress_primary_result" value="0" <?php checked( $selection, 0 ); ?>></th>
									<th colspan="4"><label for="sportspress_primary_result_0">
										<?php
										if ( sizeof( $data ) > 0 ):
											$default = end( $data );
											reset( $data );
											printf( __( 'Default (%s)', 'sportspress' ), $default->post_title );
										else:
											_e( 'Default', 'sportspress' );
										endif;
										?>
									</label></th>
								</tr>
							</tfoot>
							<?php if ( $data ): $i = 0; foreach ( $data as $row ): ?>
								<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
									<td class="radio"><input type="radio" class="sp-primary-result-option" id="sportspress_primary_result_<?php echo $row->post_name; ?>" name="sportspress_primary_result" value="<?php echo $row->post_name; ?>" <?php checked( $selection, $row->post_name ); ?>></td>
									<td class="row-title"><label for="sportspress_primary_result_<?php echo $row->post_name; ?>"><?php echo $row->post_title; ?></label></td>
									<td><?php echo $row->post_name; ?>for, <?php echo $row->post_name; ?>against</td>
									<td><p class="description"><?php echo $row->post_excerpt; ?></p></td>
									<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
								</tr>
							<?php $i++; endforeach; else: ?>
							<tr class="alternate">
								<td colspan="5"><?php _e( 'No results found.', 'sportspress' ); ?></td>
							</tr>
						<?php endif; ?>
						</table>
					</form>
					<div class="tablenav bottom">
						<div class="alignleft actions">
							<a class="button button-primary" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_result' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
							<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_result' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						</div>
						<br class="clear">
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="form-table">
		<tbody>
			<?php
			$args = array(
				'post_type' => 'sp_performance',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			);
			$data = get_posts( $args );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Player Performance', 'sportspress' ) ?>
					<p class="description"><?php _e( 'Used for events.', 'sportspress' ); ?></p>
				</th>
			    <td class="forminp">
					<table class="widefat sp-admin-config-table">
						<thead>
							<tr>
								<th class="icon" scope="col"><?php _e( 'Icon', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Variable', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Description', 'sportspress' ); ?></th>
								<th scope="col" class="edit"></th>
							</tr>
						</thead>
						<?php if ( $data ): $i = 0; foreach ( $data as $row ): ?>
							<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
								<td class="icon"><?php if ( has_post_thumbnail( $row->ID ) ) echo get_the_post_thumbnail( $row->ID, 'sportspress-fit-mini' ); ?></td>
								<td class="row-title"><?php echo $row->post_title; ?></td>
								<td><?php echo $row->post_name; ?></td>
								<td><p class="description"><?php echo $row->post_excerpt; ?></p></td>
								<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
							</tr>
						<?php $i++; endforeach; else: ?>
							<tr class="alternate">
								<td colspan="5"><?php _e( 'No results found.', 'sportspress' ); ?></td>
							</tr>
						<?php endif; ?>
					</table>
					<div class="tablenav bottom">
						<div class="alignleft actions">
							<a class="button button-primary" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_performance' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
							<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_performance' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						</div>
						<br class="clear">
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="form-table">
		<tbody>
			<?php
			$args = array(
				'post_type' => 'sp_column',
				'numberposts' => -1,
				'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC'
			);
			$data = get_posts( $args );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Table Columns', 'sportspress' ) ?>
					<p class="description"><?php _e( 'Used for league tables.', 'sportspress' ); ?></p>
				</th>
			    <td class="forminp">
					<table class="widefat sp-admin-config-table">
						<thead>
							<tr>
								<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Equation', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Rounding', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Sort Order', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Description', 'sportspress' ); ?></th>
								<th scope="col" class="edit"></th>
							</tr>
						</thead>
						<?php if ( $data ): $i = 0; foreach ( $data as $row ): ?>
							<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
								<td class="row-title"><?php echo $row->post_title; ?></td>
								<td><?php echo $row->post_name; ?></td>
								<td><?php echo sp_get_post_equation( $row->ID ); ?></td>
								<td><?php echo sp_get_post_precision( $row->ID ); ?></td>
								<td><?php echo sp_get_post_order( $row->ID ); ?></td>
								<td><p class="description"><?php echo $row->post_excerpt; ?></p></td>
								<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
							</tr>
						<?php $i++; endforeach; else: ?>
							<tr class="alternate">
								<td colspan="7"><?php _e( 'No results found.', 'sportspress' ); ?></td>
							</tr>
						<?php endif; ?>
					</table>
					<div class="tablenav bottom">
						<div class="alignleft actions">
							<a class="button button-primary" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_column' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
							<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_column' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						</div>
						<br class="clear">
					</div>
				</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="form-table">
		<tbody>
			<?php
			$args = array(
				'post_type' => 'sp_metric',
				'numberposts' => -1,
				'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC'
			);
			$data = get_posts( $args );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Player Metrics', 'sportspress' ) ?>
					<p class="description"><?php _e( 'Used for player lists.', 'sportspress' ); ?></p>
				</th>
			    <td class="forminp">
					<table class="widefat sp-admin-config-table">
						<thead>
							<tr>
								<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Variable', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Description', 'sportspress' ); ?></th>
								<th scope="col" class="edit"></th>
							</tr>
						</thead>
						<?php if ( $data ): $i = 0; foreach ( $data as $row ): ?>
							<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
								<td class="row-title"><?php echo $row->post_title; ?></td>
								<td><?php echo $row->post_name; ?></td>
								<td><p class="description"><?php echo $row->post_excerpt; ?></p></td>
								<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
							</tr>
						<?php $i++; endforeach; else: ?>
							<tr class="alternate">
								<td colspan="4"><?php _e( 'No results found.', 'sportspress' ); ?></td>
							</tr>
						<?php endif; ?>
					</table>
					<div class="tablenav bottom">
						<div class="alignleft actions">
							<a class="button button-primary" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_metric' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
							<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_metric' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						</div>
						<br class="clear">
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="form-table">
		<tbody>
			<?php
			$args = array(
				'post_type' => 'sp_statistic',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			);
			$data = get_posts( $args );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Player Statistics', 'sportspress' ) ?>
					<p class="description"><?php _e( 'Used for player lists.', 'sportspress' ); ?></p>
				</th>
			    <td class="forminp">
					<table class="widefat sp-admin-config-table">
						<thead>
							<tr>
								<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Equation', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Rounding', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Description', 'sportspress' ); ?></th>
								<th scope="col" class="edit"></th>
							</tr>
						</thead>
						<?php if ( $data ): $i = 0; foreach ( $data as $row ): ?>
							<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
								<td class="row-title"><?php echo $row->post_title; ?></td>
								<td><?php echo $row->post_name; ?></td>
								<td><?php echo sp_get_post_equation( $row->ID ); ?></td>
								<td><?php echo sp_get_post_precision( $row->ID ); ?></td>
								<td><p class="description"><?php echo $row->post_excerpt; ?></p></td>
								<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
							</tr>
						<?php $i++; endforeach; else: ?>
							<tr class="alternate">
								<td colspan="6"><?php _e( 'No results found.', 'sportspress' ); ?></td>
							</tr>
						<?php endif; ?>
					</table>
					<div class="tablenav bottom">
						<div class="alignleft actions">
							<a class="button button-primary" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_statistic' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
							<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_statistic' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						</div>
						<br class="clear">
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php do_action( 'sportspress_config_page' ); ?>
</div>