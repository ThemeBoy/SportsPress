<?php
/**
 * Config type meta box functions
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Config
 */
class SP_Meta_Box_Config {

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		self::delete_duplicate( $_POST );
	}

	public static function select( $postid, $selected = null, $groups = array() ) {

		if ( ! isset( $postid ) )
			return;

		// Initialize options array
		$options = array();

		// Add groups to options
		foreach ( $groups as $group ):
			switch ( $group ):
				case 'player_event':
					$options[ __( 'Events', 'sportspress' ) ] = array( '$eventsattended' => __( 'Attended', 'sportspress' ), '$eventsplayed' => __( 'Played', 'sportspress' ), '$eventsstarted' => __( 'Started', 'sportspress' ), '$eventssubbed' => __( 'Substituted', 'sportspress' ) );
					break;
				case 'team_event':
					$options[ __( 'Events', 'sportspress' ) ] = array( '$eventsplayed' => __( 'Played', 'sportspress' ) );
					break;
				case 'result':
					$options[ __( 'Results', 'sportspress' ) ] = self::optgroup( $postid, 'sp_result', array( 'for' => '&rarr;', 'against' => '&larr;' ), null, false );
					break;
				case 'outcome':
					$options[ __( 'Outcomes', 'sportspress' ) ] = self::optgroup( $postid, 'sp_outcome', array() );
					$options[ __( 'Outcomes', 'sportspress' ) ]['$streak'] = __( 'Streak', 'sportspress' );
					$options[ __( 'Outcomes', 'sportspress' ) ]['$last5'] = __( 'Last 5', 'sportspress' );
					$options[ __( 'Outcomes', 'sportspress' ) ]['$last10'] = __( 'Last 10', 'sportspress' );
					break;
				case 'performance':
					$options[ __( 'Performance', 'sportspress' ) ] = self::optgroup( $postid, 'sp_performance' );
					break;
				case 'metric':
					$options[ __( 'Metric', 'sportspress' ) ] = self::optgroup( $postid, 'sp_metric' );
					break;
			endswitch;
		endforeach;

		// Create array of operators
		$operators = array( '+' => '&plus;', '-' => '&minus;', '*' => '&times;', '/' => '&divide;', '(' => '(', ')' => ')' );

		// Add operators to options
		$options[ __( 'Operators', 'sportspress' ) ] = $operators;

		// Create array of constants
		$max = 10;
		$constants = array();
		for ( $i = 1; $i <= $max; $i ++ ):
			$constants[$i] = $i;
		endfor;

		// Add 100 to constants
		$constants[100] = 100;

		// Add constants to options
		$options[ __( 'Constants', 'sportspress' ) ] = (array) $constants;

		?>
			<select name="sp_equation[]">
				<option value=""><?php _e( '&mdash; Select &mdash;', 'sportspress' ); ?></option>
				<?php

				foreach ( $options as $label => $option ):
					printf( '<optgroup label="%s">', $label );

					foreach ( $option as $key => $value ):
						printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $selected, false ), $value );
					endforeach;
				
					echo '</optgroup>';
				endforeach;

				?>
			</select>
		<?php
	}

	public static function optgroup( $postid, $type = null, $variations = null, $defaults = null, $totals = true ) {
		$arr = array();

		// Get posts
		$args = array(
			'post_type' => $type,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'exclude' => $postid
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

	public static function delete_duplicate( &$post ) {
		global $wpdb;

		$key = isset( $post['sp_key'] ) ? $post['sp_key'] : null;
		if ( ! $key ) $key = $post['post_title'];
		$id = sp_array_value( $post, 'post_ID', 'var' );
		$title = sp_get_eos_safe_slug( $key, $id );

		$check_sql = "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = %s AND ID != %d LIMIT 1";
		$post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $title, $post['post_type'], $id ) );

		if ( $post_name_check ):
			wp_delete_post( $post_name_check, true );
			$post['post_status'] = 'draft';
		endif;

		return $post_name_check;
	}

}