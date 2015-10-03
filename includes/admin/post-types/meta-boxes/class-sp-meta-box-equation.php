<?php
/**
 * Equation meta box functions
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Equation
 */
class SP_Meta_Box_Equation {

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_equation', implode( ' ', sp_array_value( $_POST, 'sp_equation', array() ) ) );
	}

	public static function builder( $title = 'f(x)', $equation = '', $groups = array() ) {
		if ( $title == '' ) $title = 'f(x)';
		$options = array(); // Multidimensional equation part options
		$parts = array(); // Flattened equation part options

		// Add groups to options
		foreach ( $groups as $group ):
			switch ( $group ):
				case 'player_event':
					$options[ 'Events' ] = array( '$eventsattended' => __( 'Attended', 'sportspress' ), '$eventsplayed' => __( 'Played', 'sportspress' ), '$eventsstarted' => __( 'Started', 'sportspress' ), '$eventssubbed' => __( 'Substituted', 'sportspress' ), '$eventminutes' => __( 'Minutes', 'sportspress' ) );
					break;
				case 'team_event':
					$options[ 'Events' ] = array( '$eventsplayed' => __( 'Played', 'sportspress' ), '$eventminutes' => __( 'Minutes', 'sportspress' ) );
					break;
				case 'result':
					$options[ 'Results' ] = self::optgroup( 'sp_result', array( 'for' => '(' . __( 'for', 'sportspress' ) . ')', 'against' => '(' . __( 'against', 'sportspress' ) . ')' ), null, false );
					break;
				case 'outcome':
					$options[ 'Outcomes' ] = self::optgroup( 'sp_outcome' );
					break;
				case 'preset':
					$options[ 'Presets' ] = array( '$gamesback' => __( 'Games Back', 'sportspress' ), '$homerecord' => __( 'Home Record', 'sportspress' ), '$awayrecord' => __( 'Away Record', 'sportspress' ), '$streak' => __( 'Streak', 'sportspress' ), '$last5' => __( 'Last 5', 'sportspress' ), '$last10' => __( 'Last 10', 'sportspress' ) );
					break;
				case 'subset':
					$options[ 'Subsets' ] = array( '_home' => '@' . __( 'Home', 'sportspress' ), '_away' => '@' . __( 'Away', 'sportspress' ), '_venue' => '@' . __( 'Venue', 'sportspress' ) );
					break;
				case 'performance':
					$options[ 'Performance' ] = self::optgroup( 'sp_performance' );
					break;
				case 'metric':
					$options[ 'Metrics' ] = self::optgroup( 'sp_metric' );
					break;
			endswitch;
		endforeach;

		// Add operators to options
		$options[ 'Operators' ] = array( '+' => '&plus;', '-' => '&minus;', '*' => '&times;', '/' => '&divide;', '(' => '(', ')' => ')' );

		// Create array of constants
		$max = 10;
		$constants = array();
		for ( $i = 0; $i <= $max; $i ++ ):
			$constants[$i] = $i;
		endfor;

		// Add 100 to constants
		$constants[100] = 100;

		// Add constants to options
		$options[ 'Constants' ] = (array) $constants;

		$options = apply_filters( 'sportspress_equation_options', $options );
		?>
		<div class="sp-equation-builder">
			<div class="sp-data-table-container sp-equation-parts">
				<table class="widefat sp-data-table">
					<?php $i = 0; foreach ( $options as $label => $option ): ?>
						<tr<?php if ( $i % 2 == 0 ): ?> class="alternate"<?php endif; ?>>
							<th><?php _e( $label, 'sportspress' ); ?></th>
							<td>
								<?php foreach ( $option as $key => $value ): $parts[ $key ] = $value;
									?><span class="button" data-variable="<?php echo $key; ?>"><?php echo $value; ?></span><?php
								endforeach; ?>
							</td>
						</tr>
					<?php $i++; endforeach; ?>
				</table>
			</div>
			<div class="sp-equation">
				<span class="sp-equation-variable"><?php echo $title; ?> = </span>
				<span class="sp-equation-formula"><?php
					if ( $equation !== '' ):
						$equation = explode( ' ', $equation );
						foreach ( $equation as $part ):
							if ( array_key_exists( $part, $parts ) ) {
								$name = $parts[ $part ];
							} else {
								$name = $part;
							} ?><span class="button"><?php echo $name; ?><span class="remove">&times;</span><input type="hidden" name="sp_equation[]" value="<?php echo $part; ?>"></span><?php
						endforeach;
					endif;
				?></span>
			</div>
		</div>
		<?php
	}

	public static function optgroup( $type = null, $variations = null, $defaults = null, $totals = true ) {
		$arr = array();

		// Get posts
		$args = array(
			'post_type' => $type,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		$vars = get_posts( $args );

		// Add extra vars to the array
		if ( isset( $defaults ) && is_array( $defaults ) ):
			foreach ( $defaults as $key => $value ):
				$arr[ $key ] = $value;
			endforeach;
		endif;

		// Add vars to the array
		if ( isset( $variations ) && is_array( $variations ) ):
			foreach ( $vars as $var ):
				if ( $totals ) $arr[ '$' . $var->post_name ] = $var->post_title;
				foreach ( $variations as $key => $value ):
					$arr[ '$' . $var->post_name . $key ] = $var->post_title . ' ' . $value;
				endforeach;
			endforeach;
		else:
			foreach ( $vars as $var ):
				$arr[ '$' . $var->post_name ] = $var->post_title;
			endforeach;
		endif;

		return (array) $arr;
	}

	/**
	 * Equation part labels for localization
	 * @return null
	 */
	public static function equation_part_labels() {
		__( 'Presets', 'sportspress' );
		__( 'Operators', 'sportspress' );
		__( 'Subsets', 'sportspress' );
		__( 'Constants', 'sportspress' );
	}
}