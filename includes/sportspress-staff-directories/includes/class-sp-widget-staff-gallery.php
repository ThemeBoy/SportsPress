<?php
class SP_Widget_Staff_Gallery extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sportspress widget_staff_gallery widget_sp_staff_gallery', 'description' => __( 'A gallery of staff.', 'sportspress' ) );
		parent::__construct('sportspress-staff-gallery', __( 'Staff Gallery', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$id = empty($instance['id']) ? 0 : $instance['id'];
		if ( $id <= 0 ) return;
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$number = empty($instance['number']) ? null : $instance['number'];
		$show_all_staff_link = empty($instance['show_all_staff_link']) ? false : $instance['show_all_staff_link'];

		do_action( 'sportspress_before_widget', $args, $instance, 'staff-gallery' );
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'staff-gallery' );

		sp_get_template( 'staff-gallery.php', array( 'id' => $id, 'number' => $number, 'show_all_staff_link' => $show_all_staff_link ), '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'staff-gallery' );

		echo $after_widget;
		do_action( 'sportspress_after_widget', $args, $instance, 'staff-gallery' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);
		$instance['number'] = intval($new_instance['number']);
		$instance['show_all_staff_link'] = $new_instance['show_all_staff_link'];

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'staff-gallery' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => '', 'number' => 5, 'show_all_staff_link' => true ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
		$number = intval($instance['number']);
		$show_all_staff_link = $instance['show_all_staff_link'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'staff-gallery' );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php printf( __( 'Select %s:', 'sportspress' ), __( 'Staff Directory', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_directory',
			'name' => $this->get_field_name('id'),
			'id' => $this->get_field_id('id'),
			'selected' => $id,
			'values' => 'ID',
			'class' => 'widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ):
			sp_post_adder( 'sp_directory', __( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of staff to show:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3"></p>

		<p><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_all_staff_link'); ?>" name="<?php echo $this->get_field_name('show_all_staff_link'); ?>" value="1" <?php checked( $show_all_staff_link, 1 ); ?>>
		<label for="<?php echo $this->get_field_id('show_all_staff_link'); ?>"><?php _e( 'Display link to view all staff', 'sportspress' ); ?></label></p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'staff-gallery' );
	}
}

register_widget( 'SP_Widget_Staff_Gallery' );
