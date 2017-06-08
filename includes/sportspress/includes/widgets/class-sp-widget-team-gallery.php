<?php
class SP_Widget_Team_Gallery extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sportspress widget_team_gallery widget_sp_team_gallery', 'description' => __( 'Display a gallery of teams.', 'sportspress' ) );
		parent::__construct('sportspress-team-gallery', __( 'Team Gallery', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		
		$id = empty($instance['id']) ? 0 : $instance['id'];
		
		if ( $id <= 0 ) return;
		
		if ( 'yes' == get_option( 'sportspress_widget_unique', 'no' ) && get_the_ID() === $id ) {
			$format = get_post_meta( $id, 'sp_format', true );
			if ( 'gallery' == $format ) return;
		}

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$caption = empty($instance['caption']) ? null : $instance['caption'];
		$number = empty($instance['number']) ? null : $instance['number'];
		$columns = empty($instance['columns']) ? null : $instance['columns'];
		$orderby = empty($instance['orderby']) ? 'default' : $instance['orderby'];
		$show_all_teams_link = empty($instance['show_all_teams_link']) ? false : $instance['show_all_teams_link'];

		do_action( 'sportspress_before_widget', $args, $instance, 'team-gallery' );
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'team-gallery' );

		sp_get_template( 'team-gallery.php', array( 'id' => $id, 'title' => $caption, 'number' => $number, 'columns' => $columns, 'orderby' => $orderby , 'grouping' => 0, 'show_all_teams_link' => $show_all_teams_link ) );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'team-gallery' );

		echo $after_widget;
		do_action( 'sportspress_after_widget', $args, $instance, 'team-gallery' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);
		$instance['caption'] = strip_tags($new_instance['caption']);
		$instance['number'] = intval($new_instance['number']);
		$instance['columns'] = intval($new_instance['columns']);
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['show_all_teams_link'] = $new_instance['show_all_teams_link'];

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'team-gallery' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => '', 'caption' => '', 'number' => 5, 'columns' => 2, 'orderby' => 'default', 'show_all_teams_link' => true ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
		$caption = strip_tags($instance['caption']);
		$number = intval($instance['number']);
		$columns = intval($instance['columns']);
		$orderby = strip_tags($instance['orderby']);
		$show_all_teams_link = $instance['show_all_teams_link'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'team-gallery' );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('caption'); ?>"><?php _e( 'Heading:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="text" value="<?php echo esc_attr($caption); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php printf( __( 'Select %s:', 'sportspress' ), __( 'League Table', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_table',
			'name' => $this->get_field_name('id'),
			'id' => $this->get_field_id('id'),
			'selected' => $id,
			'values' => 'ID',
			'class' => 'widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ):
			sp_post_adder( 'sp_table', __( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of teams to show:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3"></p>

		<p><label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e( 'Columns:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" type="text" value="<?php echo esc_attr($columns); ?>" size="3"></p>

		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort by:', 'sportspress' ); ?></label>
		<select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
			<option value="default" <?php selected( 'default', $orderby ); ?>><?php _e( 'Rank', 'sportspress' ); ?></option>
			<option value="name" <?php selected( 'name', $orderby ); ?>><?php _e( 'Alphabetical', 'sportspress' ); ?></option>
			<option value="rand" <?php selected( 'rand', $orderby ); ?>><?php _e( 'Random', 'sportspress' ); ?></option>
		</select></p>

		<p><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_all_teams_link'); ?>" name="<?php echo $this->get_field_name('show_all_teams_link'); ?>" value="1" <?php checked( $show_all_teams_link, 1 ); ?>>
		<label for="<?php echo $this->get_field_id('show_all_teams_link'); ?>"><?php _e( 'Display link to view all teams', 'sportspress' ); ?></label></p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'team-gallery' );
	}
}

register_widget( 'SP_Widget_Team_Gallery' );
