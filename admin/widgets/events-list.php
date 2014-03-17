<?php
class SportsPress_Widget_Events_List extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sp_events_list', 'description' => __( 'A list of events.', 'sportspress' ) );
		parent::__construct('sp_events_list', __( 'SportsPress Events List', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$id = empty($instance['id']) ? null : $instance['id'];
		$columns = empty($instance['columns']) ? null : $instance['columns'];
		$show_all_events_link = empty($instance['show_all_events_link']) ? false : $instance['show_all_events_link'];
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo sportspress_events_list( $id, array( 'columns' => $columns, 'show_all_events_link' => $show_all_events_link ) );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);
		$instance['columns'] = (array)$new_instance['columns'];
		$instance['show_all_events_link'] = $new_instance['show_all_events_link'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => null, 'columns' => null, 'show_all_events_link' => true ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
		$columns = $instance['columns'];
		$show_all_events_link = $instance['show_all_events_link'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_calendar',
			'show_option_all' => __( 'All', 'sportspress' ),
			'name' => $this->get_field_name('id'),
			'id' => $this->get_field_id('id'),
			'selected' => $id,
			'values' => 'ID',
			'class' => 'sp-events-calendar-select widefat',
		);
		if ( ! sportspress_dropdown_pages( $args ) ):
			sportspress_post_adder( 'sp_calendar', __( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p class="sp-prefs">
			<?php _e( 'Columns:', 'sportspress' ); ?><br>
			<?php 
			$the_columns = array(
				'event' => __( 'Event', 'sportspress' ),
				'teams' => __( 'Teams', 'sportspress' ),
				'time' => __( 'Time', 'sportspress' ),
				'article' => __( 'Article', 'sportspress' ),
			);
			$field_name = $this->get_field_name('columns') . '[]';
			$field_id = $this->get_field_id('columns');
			?>
			<?php foreach ( $the_columns as $key => $label ): ?>
				<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $key; ?>" value="<?php echo $key; ?>" <?php if ( $columns === null || in_array( $key, $columns ) ): ?>checked="checked"<?php endif; ?>><?php echo $label; ?></label>
			<?php endforeach; ?>
		</p>

		<p class="sp-events-calendar-show-all-toggle<?php if ( ! $id ): ?> hidden<?php endif; ?>"><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_all_events_link'); ?>" name="<?php echo $this->get_field_name('show_all_events_link'); ?>" value="1" <?php checked( $show_all_events_link, 1 ); ?>>
		<label for="<?php echo $this->get_field_id('show_all_events_link'); ?>"><?php _e( 'Display link to view all events', 'sportspress' ); ?></label></p>
<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "SportsPress_Widget_Events_List" );' ) );