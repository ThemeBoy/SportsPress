<?php
class SportsPressEventSettingsPage {
	public function __construct() {
		global $sportspress_options;
		$this->options =& $sportspress_options;
		add_action( 'admin_init', array( $this, 'page_init' ), 1 );
	}

	function page_init() {
		register_setting(
			'sportspress_events',
			'sportspress',
			'sportspress_options_validate'
		);
		
		add_settings_section(
			'event',
			__( 'Event Options', 'sportspress' ),
			'',
			'sportspress_events'
		);
		
		add_settings_field(	
			'delimiter',
			__( 'Delimiter', 'sportspress' ),
			array( $this, 'delimiter_callback' ),
			'sportspress_events',
			'event'
		);
		
		add_settings_field(	
			'results',
			__( 'Results', 'sportspress' ),
			array( $this, 'results_callback' ),
			'sportspress_events',
			'event'
		);
		
		add_settings_field(	
			'outcomes',
			__( 'Outcomes', 'sportspress' ),
			array( $this, 'outcomes_callback' ),
			'sportspress_events',
			'event'
		);
	}

	function delimiter_callback() {
		$teams_delimiter = sportspress_array_value( $this->options, 'event_teams_delimiter', __( 'vs', 'sportspress' ) );
		?>
		<fieldset class="sp-custom-input-wrapper">
			<legend class="screen-reader-text"><span><?php _e( 'Delimiter', 'sportspress' ); ?></span></legend>
			<?php $delimiters = array( 'vs', 'v', '—', '/' ); ?>
			<?php foreach ( $delimiters as $delimiter ): ?>
				<label title="<?php echo $delimiter; ?>"><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="<?php echo $delimiter; ?>" data-example="<?php _e( 'Team', 'sportspress' ); ?> <?php echo $delimiter; ?> <?php _e( 'Team', 'sportspress' ); ?>" <?php checked( $delimiter, $teams_delimiter ); ?>> <span><?php _e( 'Team', 'sportspress' ); ?> <?php echo $delimiter; ?> <?php _e( 'Team', 'sportspress' ); ?></span></label><br>
			<?php endforeach; ?>
			<label><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="\c\u\s\t\o\m" <?php checked( false, in_array( $teams_delimiter, $delimiters ) ); ?>> <?php _e( 'Custom:', 'sportspress' ); ?> </label><input type="text" class="small-text value" name="sportspress[event_teams_delimiter]" value="<?php echo $teams_delimiter; ?>" data-example-format="<?php _e( 'Team', 'sportspress' ); ?> __val__ <?php _e( 'Team', 'sportspress' ); ?>">
			<span class="example"><?php _e( 'Team', 'sportspress' ); ?> <?php echo $teams_delimiter; ?> <?php _e( 'Team', 'sportspress' ); ?></span>
		</fieldset>
		<?php
	}

	function results_callback() {
		$main_result = sportspress_array_value( $this->options, 'main_result', 0 );

		$args = array(
			'post_type' => 'sp_result',
			'numberposts' => -1,
			'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
		);
		$data = get_posts( $args );
		?>
		<fieldset>
			<table class="widefat sp-admin-config-table">
				<thead>
					<tr>
						<th scope="col"><?php _e( 'Primary', 'sportspress' ); ?></th>
						<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
						<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="radio"><input type="radio" id="sportspress_main_result_0" name="sportspress[main_result]" value="0" <?php checked( $main_result, 0 ); ?>></th>
						<th colspan="2"><label for="sportspress_main_result_0">
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
				<?php $i = 0; foreach ( $data as $row ): ?>
					<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
						<td class="radio"><input type="radio" id="sportspress_main_result_<?php echo $row->post_name; ?>" name="sportspress[main_result]" value="<?php echo $row->post_name; ?>" <?php checked( $main_result, $row->post_name ); ?>></td>
						<td class="row-title"><label for="sportspress_main_result_<?php echo $row->post_name; ?>"><?php echo $row->post_title; ?></label></td>
						<td><?php echo $row->post_name; ?>for / <?php echo $row->post_name; ?>against</td>
					</tr>
				<?php $i++; endforeach; ?>
			</table>
			<div class="tablenav bottom">
				<div class="alignleft actions">
					<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_result' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
					<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_result' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
				</div>
				<br class="clear">
			</div>
		</fieldset>
		<?php
	}

	function outcomes_callback() {
		$args = array(
			'post_type' => 'sp_outcome',
			'numberposts' => -1,
			'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
		);
		$data = get_posts( $args );
		?>
		<fieldset>
			<table class="widefat sp-admin-config-table">
				<thead>
					<tr>
						<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
						<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
					</tr>
				</thead>
				<?php $i = 0; foreach ( $data as $row ): ?>
					<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
						<td class="row-title"><?php echo $row->post_title; ?></td>
						<td><?php echo $row->post_name; ?></td>
					</tr>
				<?php $i++; endforeach; ?>
			</table>
			<div class="tablenav bottom">
				<div class="alignleft actions">
					<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_outcome' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
					<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_outcome' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
				</div>
				<br class="clear">
			</div>
		</fieldset>
		<?php
	}
}

if ( is_admin() )
	$sportspress_event_settings_page = new SportsPressEventSettingsPage();
