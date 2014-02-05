<?php
class SP_Widget_Countdown extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_countdown widget_sp_countdown', 'description' => __( 'SportsPress widget.', 'sportspress' ) );
		parent::__construct('sp_countdown', __( 'Countdown', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __( 'Countdown', 'sportspress' ) : $instance['title'], $instance, $this->id_base);
		echo $before_widget;
		$id = empty($instance['id']) ? null : $instance['id'];
		if ( $title )
			echo $before_title . $title . $after_title;
		echo sportspress_countdown( $instance );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => '' ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php _e( 'Event:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_event',
			'name' => $this->get_field_name('id'),
			'id' => $this->get_field_id('id'),
			'selected' => $id,
			'show_option_all' => '(' . __( 'Next Event', 'premier' ) . ')',
			'values' => 'ID',
			'class' => 'widefat',
			'show_dates' => true,
			'post_status' => 'future',
		);
		if ( ! sportspress_dropdown_pages( $args ) ):
			sportspress_post_adder( 'sp_event' );
		endif;
		?>
		</p>
<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "SP_Widget_Countdown" );' ) );
