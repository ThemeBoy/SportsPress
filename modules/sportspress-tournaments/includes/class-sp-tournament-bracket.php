<?php
/**
 * Tournament Bracket Class
 *
 * The SportsPress tournament bracket class handles individual tournament bracket data.
 *
 * @class 		SP_Tournament_Bracket
 * @version		1.0
 * @package		SportsPress_Tournament_Brackets
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Tournament_Bracket {

	/** @var int The post ID. */
	public $ID;

	/** @var object The actual post object. */
	public $post;

	/**
	 * Constructor
	 */
	public function __construct( $post ) {
		if ( $post instanceof WP_Post || $post instanceof SP_Custom_Post ):
			$this->ID   = absint( $post->ID );
			$this->post = $post;
		else:
			$this->ID  = absint( $post );
			$this->post = get_post( $this->ID );
		endif;
	}

	/**
	 * __isset function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return bool
	 */
	public function __isset( $key ) {
		return metadata_exists( 'post', $this->ID, 'sp_' . $key );
	}

	/**
	 * __get function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return bool
	 */
	public function __get( $key ) {
		if ( ! isset( $key ) ):
			return $this->post;
		else:
			$value = get_post_meta( $this->ID, 'sp_' . $key, true );
		endif;

		return $value;
	}

	/**
	 * Get the post data.
	 *
	 * @access public
	 * @return object
	 */
	public function get_post_data() {
		return $this->post;
	}

	/**
	 * Returns formatted data
	 *
	 * @access public
	 * @param bool $admin
	 * @return array
	 */
	public function data( $admin = false ) {

		// Get labels
		$labels = get_post_meta( $this->ID, 'sp_labels', true );

		// Get events
		$events = get_post_meta( $this->ID, 'sp_event', false );
		
		// Get number of rounds
		$rounds = get_post_meta( $this->ID, 'sp_rounds', true );
		if ( $rounds === '' ) $rounds = 3;

		// Initialize reference
		$reference = array(
			0 => array(
				'margin' => 0,
				'spacing' => 1,
				'height' => 3,
			)
		);

		// Loop through rounds
		for ( $i = 1; $i < $rounds; $i++ ):

			// Add margin, spacing, and height to reference array
			$margin = pow( 2, $i ) + pow( 2, $i - 1 ) - 1;
			$spacing = pow( 2, $i + 1 ) + pow( 2, $i ) - 1;
			$reference[ $i ] = array(
				'margin' => $margin,
				'spacing' => $spacing,
				'height' => $spacing,
			);

			// Update total rows
			$rows = pow( 2, $i + 2 ) + pow( 2, $i + 1 ) - 1;

		endfor;

		// Initialize data
		$data = array();

		// Loop through rows
		for ( $row = 0; $row < $rows; $row++ ):

			// Loop through columns
			for ( $col = 0; $col < $rounds; $col++ ):

				// Get measurements from reference
				$measurements = sp_array_value( $reference, $col, array() );
				$margin = sp_array_value( $measurements, 'margin', 0 );
				$spacing = sp_array_value( $measurements, 'spacing', 0 );
				$height = sp_array_value( $measurements, 'height', 0 );

				if ( $admin ):

					// Add event, team, or spacer
					if ( $row % ( 6 * pow( 2, $col ) ) === $margin + 1 ):
						$data[ $row ][ $col ] = array(
							'type' => 'event',
							'rows' => $height,
						);
					elseif ( $row % ( 6 * pow( 2, $col ) ) === $margin ):
						$data[ $row ][ $col ] = array(
							'type' => 'team',
							'rows' => 1,
						);
					elseif ( $row % ( 6 * pow( 2, $col ) ) === $margin + $height + 1 ):
						$data[ $row ][ $col ] = array(
							'type' => 'team',
							'rows' => 1,
						);
					elseif ( $row === 0 ):
						$data[ $row ][ $col ] = array(
							'type' => 'spacer',
							'rows' => $margin,
						);
					elseif ( $row % ( 6 * pow( 2, $col ) ) === $margin + $height + 2 ):
						$data[ $row ][ $col ] = array(
							'type' => 'spacer',
							'rows' => min( $spacing, $rows - $row ),
						);
					endif;

				else:

					// Not admin

				endif;
			endfor;

		endfor;

		return array( $labels, $data, $rounds, $rows );
	}
}
