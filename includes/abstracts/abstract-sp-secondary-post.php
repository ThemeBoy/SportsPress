<?php
/**
 * Abstract Secondary Post Class
 *
 * The SportsPress secondary post class extends custom posts with handling of secondary post types.
 *
 * @class 		SP_Secondary_Post
 * @version   2.5.3
 * @package		SportsPress/Abstracts
 * @category	Abstract Class
 * @author 		ThemeBoy
 */
abstract class SP_Secondary_Post extends SP_Custom_Post {

  /** @var string The date filter for events. */
  public $date = 0;

  /** @var string The date to range from. */
  public $from = 'now';

  /** @var string The date to range to. */
  public $to = 'now';

  /** @var string The number of days to query in the past. */
  public $past = 0;

  /** @var string The number of days to query in the future. */
  public $future = 0;

  /** @var boolean Determines whether the date range is relative. */
  public $relative = false;

  /**
   * __construct function.
   *
   * @access public
   * @param mixed $post
   */
  public function __construct( $post ) {
    if ( $post instanceof WP_Post || $post instanceof SP_Secondary_Post ):
      $this->ID   = absint( $post->ID );
      $this->post = $post;
    else:
      $this->ID  = absint( $post );
      $this->post = get_post( $this->ID );
    endif;
  }

  public function range( $where = '', $format = 'Y-m-d' ) {
    $from = new DateTime( $this->from );
    $to = new DateTime( $this->to );

    $to->modify( '+1 day' );

    $where .= " AND post_date BETWEEN '" . $from->format( $format ) . "' AND '" . $to->format( $format ) . "'";

    return $where;
  }

  public function relative( $where = '', $format = 'Y-m-d' ) {
    $from = new DateTime( 'now' );
    $to = new DateTime( 'now' );

    $from->modify( '-' . abs( (int) $this->past ) . ' day' );
    $to->modify( '+' . abs( (int) $this->future ) . ' day' );

    $to->modify( '+1 day' );

    $where .= " AND post_date BETWEEN '" . $from->format( $format ) . "' AND '" . $to->format( $format ) . "'";

    return $where;
  }
}
