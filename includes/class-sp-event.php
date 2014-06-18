<?php
/**
 * Event Class
 *
 * The SportsPress event class handles individual event data.
 *
 * @class 		SP_Event
 * @version		0.8.4
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Event extends SP_Custom_Post{
	
	public function status() {
		$post_status = $this->post->post_status;
		$results = get_post_meta( $this->ID, 'sp_results', true );
		foreach( $results as $result ) {
			if ( count( array_filter( $result ) ) > 0 ) {
				return 'results';
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
		$data = sp_array_combine( $teams, $results );

		if ( $admin ):
			return array( $columns, $usecolumns, $data );
		else:
			// Add outcome to result columns
			$columns['outcome'] = __( 'Outcome', 'sportspress' );
			if ( ! is_array( $usecolumns ) )
				$usecolumns = array();
			foreach ( $columns as $key => $label ):
				if ( ! in_array( $key, $usecolumns ) ):
					unset( $columns[ $key ] );
				endif;
			endforeach;
			$data[0] = $columns;
			return $data;
		endif;
	}

	public function performance( $admin = false ) {
		$teams = (array)get_post_meta( $this->ID, 'sp_team', false );
		$performance = (array)get_post_meta( $this->ID, 'sp_players', true );
		$labels = sp_get_var_labels( 'sp_performance' );
		$columns = get_post_meta( $this->ID, 'sp_columns', true );
		$output = array();
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
			endforeach;

			foreach( $totals as $key => $value ):
				$manual_total = sp_array_value( sp_array_value( $performance, 0, array() ), $key, null );
				if ( $manual_total != null ):
					$totals[ $key ] = $manual_total;
				endif;
			endforeach;

			$lineup = array_filter( $data, array( $this, 'lineup_filter' ) );
			$subs = array_filter( $data, array( $this, 'sub_filter' ) );

			foreach ( $subs as $player_id => $player ):
				if ( ! $player_id )
					continue;

				$sub = sp_array_value( $player, 'sub', 0 );

				if ( ! $sub )
					continue;

				$lineup[ $sub ]['sub'] = $player_id;
			endforeach;

			$output[ $team_id ] = array(
				'lineup' 	=> $lineup,
				'subs' 		=> $subs,
				'total' 	=> $totals
			);
		endforeach;

		if ( $admin ):
			return array( $labels, $columns, $performance, $teams );
		else:
			// Add status to performance labels
			$labels['status'] = __( 'Status', 'sportspress' );
			if ( ! is_array( $columns ) )
				$columns = array();
			foreach ( $labels as $key => $label ):
				if ( ! in_array( $key, $columns ) ):
					unset( $labels[ $key ] );
				endif;
			endforeach;
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
