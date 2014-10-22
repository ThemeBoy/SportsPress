<?php
/**
 * Event Class
 *
 * The SportsPress event class handles individual event data.
 *
 * @class 		SP_Event
 * @version		1.3
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Event extends SP_Custom_Post{
	
	public function status() {
		$post_status = $this->post->post_status;
		$results = get_post_meta( $this->ID, 'sp_results', true );
		if ( is_array( $results ) ) {
			foreach( $results as $result ) {
				$result = array_filter( $result );
				if ( count( $result ) > 0 ) {
					return 'results';
				}
			}
		}
		return $post_status;
	}

	public function results( $admin = false ) {
		$teams = (array)get_post_meta( $this->ID, 'sp_team', false );
		$results = (array)get_post_meta( $this->ID, 'sp_results', true );

		// Get columns from result variables
		$columns = sp_get_var_labels( 'sp_result' );

		// Get result columns to display
		$usecolumns = get_post_meta( $this->ID, 'sp_result_columns', true );

		// Get results for all teams
		$data = sp_array_combine( $teams, $results, true );

		if ( $admin ):
			return array( $columns, $usecolumns, $data );
		else:
			// Add outcome to result columns
			$columns['outcome'] = __( 'Outcome', 'sportspress' );
			if ( is_array( $usecolumns ) ):
				foreach ( $columns as $key => $label ):
					if ( ! in_array( $key, $usecolumns ) ):
						unset( $columns[ $key ] );
					endif;
				endforeach;
			endif;
			$data[0] = $columns;
			return $data;
		endif;
	}

	public function performance( $admin = false ) {
		$teams = get_post_meta( $this->ID, 'sp_team', false );
		$performance = (array)get_post_meta( $this->ID, 'sp_players', true );
		$labels = sp_get_var_labels( 'sp_performance' );
		$columns = get_post_meta( $this->ID, 'sp_columns', true );
		if ( is_array( $teams ) ):
			foreach( $teams as $i => $team_id ):
				$players = sp_array_between( (array)get_post_meta( $this->ID, 'sp_player', false ), 0, $i );
				$data = sp_array_combine( $players, sp_array_value( $performance, $team_id, array() ) );

				$totals = array();
				foreach( $labels as $key => $label ):
					$totals[ $key ] = 0;
				endforeach;

				foreach( $data as $player_id => $player_performance ):
					foreach( $labels as $key => $label ):
						if ( array_key_exists( $key, $totals ) ):
							$totals[ $key ] += sp_array_value( $player_performance, $key, 0 );
						endif;
					endforeach;
					if ( ! array_key_exists( 'number', $player_performance ) ):
						$performance[ $team_id ][ $player_id ]['number'] = get_post_meta( $player_id, 'sp_number', true );
					endif;
					if ( ! array_key_exists( 'position', $player_performance ) || $player_performance['position'] == null ):
						$performance[ $team_id ][ $player_id ]['position'] = get_post_meta( $player_id, 'sp_position', true );
					endif;
				endforeach;

				foreach( $totals as $key => $value ):
					$manual_total = sp_array_value( sp_array_value( $performance, 0, array() ), $key, null );
					if ( $manual_total != null ):
						$totals[ $key ] = $manual_total;
					endif;
				endforeach;
			endforeach;
		endif;

		if ( $admin ):
			return array( $labels, $columns, $performance, $teams );
		else:
			// Add position to performance labels
			$labels = array_merge( array( 'position' => __( 'Position', 'sportspress' )  ), $labels );
			if ( is_array( $columns ) ):
				foreach ( $labels as $key => $label ):
					if ( ! in_array( $key, $columns ) ):
						unset( $labels[ $key ] );
					endif;
				endforeach;
			endif;
			$performance[0] = $labels;
			return $performance;
		endif;
	}

	public function lineup_filter( $v ) {
		return sp_array_value( $v, 'status', 'lineup' ) == 'lineup';
	}

	public function sub_filter( $v ) {
		return sp_array_value( $v, 'status', 'lineup' ) == 'sub';
	}
}
