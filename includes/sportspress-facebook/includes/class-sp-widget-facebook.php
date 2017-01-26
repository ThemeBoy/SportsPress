<?php
class SP_Widget_Facebook extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sportspress widget_sp_facebook', 'description' => __( 'Embed and promote your Facebook Page.', 'sportspress' ) );
		parent::__construct('sportspress-facebook', __( 'Facebook', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$url = empty($instance['url']) ? 'https://www.facebook.com/themeboy/' : $instance['url'];
		$tabs = empty($instance['tabs']) ? array() : $instance['tabs'];
		$allow_override = empty($instance['allow_override']) ? false : $instance['allow_override'];

		do_action( 'sportspress_before_widget', $args, $instance, 'facebook' );
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'facebook' );

		// Override with team's Page URL
		if ( $allow_override ) {
			$id = get_the_ID();
			if ( $id ) {
				$meta = get_post_meta( $id, 'sp_facebook', true );
				if ( ! empty( $meta ) ) $url = $meta;
			}
		}

		sp_get_template( 'facebook.php', array( 'url' => $url, 'tabs' => $tabs ), '', SP_FACEBOOK_DIR . 'templates/' );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'facebook' );

		echo $after_widget;
		do_action( 'sportspress_after_widget', $args, $instance, 'facebook' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['url'] = esc_url($new_instance['url']);
		$instance['allow_override'] = $new_instance['allow_override'];
		$instance['tabs'] = (array)($new_instance['tabs']);

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'facebook' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'url' => '', 'allow_override' => true, 'tabs' => array( 'timeline' ) ) );
		$title = strip_tags($instance['title']);
		$url = esc_url($instance['url']);
		$allow_override = $instance['allow_override'];
		$tabs = $instance['tabs'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'facebook' );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"></p>

		<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e( 'Page URL:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($url); ?>" placeholder="https://www.facebook.com/themeboy/"></p>

		<p><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('allow_override'); ?>" name="<?php echo $this->get_field_name('allow_override'); ?>" value="1" <?php checked( $allow_override, 1 ); ?>>
		<label for="<?php echo $this->get_field_id('allow_override'); ?>">Use team's Page URL if available</label><br>

		<p class="sp-prefs">
			<?php _e( 'Tabs:', 'sportspress' ); ?><br>
			<?php 
			$options = array(
				'timeline' => 'Timeline',
				'events' => 'Events',
				'messages' => 'Messages',
			);

			$field_name = $this->get_field_name('tabs') . '[]';
			$field_id = $this->get_field_id('tabs');
			?>
			<?php foreach ( $options as $key => $label ): ?>
				<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $key; ?>" value="<?php echo $key; ?>" <?php if ( in_array( $key, $tabs ) ): ?>checked="checked"<?php endif; ?>><?php echo $label; ?></label>
			<?php endforeach; ?>
		</p>
		
		<p class="description">To enable messaging on your Facebook page go to your Page <strong>Settings</strong>. In the row <strong>Messages</strong> check <em>Allow people to contact my Page privately by showing the Message button</em>.</p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'facebook' );
	}
}

register_widget( 'SP_Widget_Facebook' );
