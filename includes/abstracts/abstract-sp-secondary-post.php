<?php
/**
 * Abstract Secondary Post Class
 *
 * The SportsPress secondary post class extends custom posts with handling of secondary post types.
 *
 * @class 		SP_Secondary_Post
 * @version		2.5
 * @package		SportsPress/Abstracts
 * @category	Abstract Class
 * @author 		ThemeBoy
 */
abstract class SP_Secondary_Post extends SP_Custom_Post {
  public function range( $where = '', $format = 'Y-m-d' ) {
    $from = new DateTime( $this->from, new DateTimeZone( get_option( 'timezone_string' ) ) );
    $to = new DateTime( $this->to, new DateTimeZone( get_option( 'timezone_string' ) ) );
    $to->modify( '+1 day' );
    $where .= " AND post_date BETWEEN '" . $from->format( $format ) . "' AND '" . $to->format( $format ) . "'";
    return $where;
  }

  public function relative( $where = '', $format = 'Y-m-d' ) {
    $datetimezone = new DateTimeZone( get_option( 'timezone_string' ) );
    $from = new DateTime( 'now', $datetimezone );
    $to = new DateTime( 'now', $datetimezone );

    $from->modify( '-' . abs( (int) $this->past ) . ' day' );
    $to->modify( '+' . abs( (int) $this->future ) . ' day' );

    $to->modify( '+1 day' );

    $where .= " AND post_date BETWEEN '" . $from->format( $format ) . "' AND '" . $to->format( $format ) . "'";

    return $where;
  }
}
