<?php
/**
 * Staff Directory Meta Boxes
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Staff_Directories
 * @version     1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Staff_Directory_Meta_Boxes
 */
class SP_Staff_Directory_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_directory_meta', array( $this, 'save' ), 10, 2 );
		add_action( 'sportspress_process_sp_staff_meta', array( $this, 'staff_save' ), 15, 2 );
		add_action( 'sportspress_process_sp_team_meta', array( $this, 'team_save' ), 15, 2 );
	}

	/**
	 * Add Meta boxes
	 */
	public function add_meta_boxes() {
		global $post;

		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), array( $this, 'shortcode' ), 'sp_directory', 'side', 'default' );
		add_meta_box( 'sp_formatdiv', __( 'Layout', 'sportspress' ), array( $this, 'format' ), 'sp_directory', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), array( $this, 'details' ), 'sp_directory', 'side', 'default' );
		add_meta_box( 'sp_datadiv', __( 'Staff Directory', 'sportspress' ), array( $this, 'data' ), 'sp_directory', 'normal', 'high' );
		add_meta_box( 'sp_editordiv', __( 'Description', 'sportspress' ), array( $this, 'editor' ), 'sp_directory', 'normal', 'high' );

		// Add contact info meta box to staff
		add_meta_box( 'sp_contactdiv', __( 'Contact Info', 'sportspress' ), array( $this, 'staff_contact' ), 'sp_staff', 'side', 'default' );

		// Add directories meta box to team
		if ( isset( $post ) && isset( $post->ID ) ):
			add_meta_box( 'sp_directoriesdiv', __( 'Staff Directories', 'sportspress' ), array( $this, 'team_directories' ), 'sp_team', 'normal', 'high' );
		endif;
	}

	/**
	 * Remove default meta boxes
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'sp_seasondiv', 'sp_directory', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_directory', 'side' );
		remove_meta_box( 'sp_rolediv', 'sp_directory', 'side' );
	}

	/**
	 * Output the shortcode metabox
	 */
	public static function shortcode( $post ) {
		$the_format = get_post_meta( $post->ID, 'sp_format', true );
		if ( ! $the_format ) $the_format = 'list';
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p><input type="text" value="<?php sp_shortcode_template( 'staff_' . $the_format, $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<?php
	}

	/**
	 * Output the format metabox
	 */
	public static function format( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$the_format = get_post_meta( $post->ID, 'sp_format', true );
		?>
		<div id="post-formats-select">
			<?php foreach ( SP()->formats->list as $key => $format ): ?>
				<input type="radio" name="sp_format" class="post-format" id="post-format-<?php echo $key; ?>" value="<?php echo $key; ?>" <?php checked( true, ( $key == 'list' && ! $the_format ) || $the_format == $key ); ?>> <label for="post-format-<?php echo $key; ?>" class="post-format-icon post-format-<?php echo $key; ?>"><?php echo $format; ?></label><br>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Output the details metabox
	 */
	public static function details( $post ) {
		$taxonomies = get_object_taxonomies( 'sp_directory' );
		$team_id = get_post_meta( $post->ID, 'sp_team', true );
		?>
		<div>
			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
			<p class="sp-tab-select">
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team',
					'show_option_all' => __( 'All', 'sportspress' ),
					'selected' => $team_id,
					'values' => 'ID',
				);
				if ( ! sp_dropdown_pages( $args ) ):
					sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
				endif;
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Output the data metabox
	 */
	public static function data( $post ) {
		$directory = new SP_Staff_Directory( $post );
		list( $labels, $columns, $data ) = $directory->data( true );
		$staff = get_post_meta( $post->ID, 'sp_staff', true );
		self::table( $labels, $columns, $data, $staff );
	}

	/**
	 * Output the editor metabox
	 */
	public static function editor( $post ) {
		wp_editor( $post->post_content, 'content' );
	}

	/**
	 * Save meta boxes data
	 */
	public static function save( $post_id, $post ) {
		// Format
		update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'list' ) );

		// Columns
		sp_update_post_meta_recursive( $post_id, 'sp_column_group', sp_array_value( $_POST, 'sp_column_group', array() ) );

		// Details
		update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_job', 0 ), 'sp_job' );
		sp_update_post_meta_recursive( $post_id, 'sp_staff', sp_array_value( $_POST, 'sp_staff', array() ) );

		// Data
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
		update_post_meta( $post_id, 'sp_staffs', sp_array_value( $_POST, 'sp_staffs', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $labels, $columns = array(), $data = null, $staff = null ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-staff-directory-table sp-sortable-table">
				<thead>
					<tr>
						<th class="icon">&nbsp;</th>
						<th><?php _e( 'Job', 'sportspress' ); ?></th>
						<th><?php _e( 'Name', 'sportspress' ); ?></th>
						<?php foreach ( $labels as $key => $label ): ?>
							<th><label for="sp_columns_<?php echo $key; ?>">
								<input type="checkbox" name="sp_columns[]" value="<?php echo $key; ?>" id="sp_columns_<?php echo $key; ?>" <?php checked( ! is_array( $columns ) || in_array( $key, $columns ) ); ?>>
								<?php echo $label; ?>
							</label></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) && sizeof( $data ) > 0 ):
						$i = 0;
						foreach ( $data as $staff_id => $staff_data ):
							if ( !$staff_id ) continue;

							$default_name = sp_array_value( $staff_data, 'name', '' );
							if ( $default_name == null )
								$default_name = get_the_title( $staff_id );

							$role = sp_array_value( $staff_data, 'role', '&mdash;' );
							$phone = sp_array_value( $staff_data, 'phone', '&mdash;' );
							$email = sp_array_value( $staff_data, 'email', '&mdash;' );
							?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
								<td class="icon">
									<span class="dashicons dashicons-menu post-state-format"></span>
									<input type="hidden" name="sp_staffs[]" value="<?php echo $staff_id; ?>">
								</td>
								<td><?php echo $role; ?></td>
								<td><?php echo $default_name; ?></span></td>
								<td><?php echo $phone; ?></td>
								<td><?php echo $email; ?></td>
							</tr>
							<?php
							$i++;
						endforeach;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="<?php $colspan = sizeof( $labels ) + 4; echo $colspan; ?>">
							<?php
							if ( $data === null ) printf( __( 'Select %s', 'sportspress' ), __( 'Details', 'sportspress' ) );
							else _e( 'No results found.', 'sportspress' );
							?>
						</td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Staff contact info meta box
	 */
	public static function staff_contact( $post ) {
		$phone = get_post_meta( $post->ID, 'sp_phone', true );
		$email = get_post_meta( $post->ID, 'sp_email', true );
		?>
		<p>
			<strong><?php _e( 'Phone', 'sportspress' ); ?></strong>
			<?php if ( ! empty( $phone ) ): ?>
				<a class="sp-link" href="tel:<?php echo $phone; ?>" title="<?php _e( 'Phone', 'sportspress' ); ?>"></a>
			<?php endif; ?>
		</p>
		<p><input type="text" id="sp_phone" name="sp_phone" value="<?php echo esc_attr( $phone ); ?>"></p>

		<p>
			<strong><?php _e( 'Email', 'sportspress' ); ?></strong>
			<?php if ( ! empty( $email ) ): ?>
				<a class="sp-link" href="mailto:<?php echo $email; ?>" title="<?php _e( 'Email', 'sportspress' ); ?>"></a>
			<?php endif; ?>
		</p>
		<p><input type="text" id="sp_email" name="sp_email" value="<?php echo esc_attr( $email ); ?>"></p>
		<?php
	}

	/**
	 * Save staff meta box
	 */
	public static function staff_save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_phone', sp_array_value( $_POST, 'sp_phone', '' ) );
		update_post_meta( $post_id, 'sp_email', sp_array_value( $_POST, 'sp_email', '' ) );
	}


	/**
	 * Team staff directories meta box
	 */
	public static function team_directories( $post ) {
		global $pagenow;

		if ( $pagenow != 'post-new.php' ):

			if ( ! $post->ID ) $data = null;

			$args = array(
				'post_type' => 'sp_directory',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'meta_key' => 'sp_team',
				'meta_value' => $post->ID,
			);
			$data = get_posts( $args );

			$checked = (array) get_post_meta( $post->ID, 'sp_directory' );

			self::team_directories_table( $data, $checked );

		else:

			printf( __( 'No results found.', 'sportspress' ) );

		endif;
	}

	/**
	 * Admin edit team directories table
	 */
	public static function team_directories_table( $data = array(), $checked = array() ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-team-directory-table sp-select-all-range">
				<thead>
					<tr>
						<th class="check-column"><input class="sp-select-all" type="checkbox"></th>
						<th class="column-directory">
							<?php _e( 'Directory', 'sportspress' ); ?>
						</th>
						<th class="column-league">
							<?php _e( 'Competition', 'sportspress' ); ?>
						</th>
						<th class="column-season">
							<?php _e( 'Season', 'sportspress' ); ?>
						</th>
						<th class="column-role">
							<?php _e( 'Job', 'sportspress' ); ?>
						</th>
						<th class="column-layout">
							<?php _e( 'Layout', 'sportspress' ); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) ):
						if ( sizeof( $data ) > 0 ):
							$i = 0;
							foreach ( $data as $directory ):
								$format = get_post_meta( $directory->ID, 'sp_format', true );
								?>
								<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
									<td>
										<input type="checkbox" name="sp_directory[]" id="sp_directory_<?php echo $directory->ID; ?>" value="<?php echo $directory->ID; ?>" <?php checked( in_array( $directory->ID, $checked ) ); ?>>
									</td>
									<td>
										<a href="<?php echo get_edit_post_link( $directory->ID ); ?>">
											<?php echo $directory->post_title; ?>
										</a>
									</td>
									<td><?php echo get_the_terms ( $directory->ID, 'sp_league' ) ? the_terms( $directory->ID, 'sp_league' ) : __( 'All', 'sportspress' ); ?></td>
									<td><?php echo get_the_terms ( $directory->ID, 'sp_season' ) ? the_terms( $directory->ID, 'sp_season' ) : __( 'All', 'sportspress' ); ?></td>
									<td><?php echo get_the_terms ( $directory->ID, 'sp_role' ) ? the_terms( $directory->ID, 'sp_role' ) : __( 'All', 'sportspress' ); ?></td>
									<td><?php echo sp_array_value( SP()->formats->directory, $format, '&mdash;' ); ?></td>
								</tr>
								<?php
								$i++;
							endforeach;
						else:
							?>
							<tr class="sp-row alternate">
								<td colspan="7">
									<?php _e( 'No results found.', 'sportspress' ); ?>
								</td>
							</tr>
							<?php
						endif;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="7">
							<?php printf( __( 'Select %s', 'sportspress' ), __( 'Details', 'sportspress' ) ); ?>
						</td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Save team meta box
	 */
	public static function team_save( $post_id, $post ) {
		sp_update_post_meta_recursive( $post_id, 'sp_directory', sp_array_value( $_POST, 'sp_directory', array() ) );
	}
}

new SP_Staff_Directory_Meta_Boxes();