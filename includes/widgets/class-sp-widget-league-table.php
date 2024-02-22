<?php
class SP_Widget_League_Table extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_sportspress widget_league_table widget_sp_league_table',
			'description' => esc_attr__( 'Display a league table.', 'sportspress' ),
		);
		parent::__construct( 'sportspress-league-table', esc_attr__( 'League Table', 'sportspress' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$id = empty( $instance['id'] ) ? 0 : $instance['id'];

		if ( $id <= 0 ) {
			return;
		}

		if ( 'yes' == get_option( 'sportspress_widget_unique', 'no' ) && get_the_ID() === $id ) {
			return;
		}

		$title                = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$caption              = empty( $instance['caption'] ) ? null : $instance['caption'];
		$number               = empty( $instance['number'] ) ? null : $instance['number'];
		$columns              = empty( $instance['columns'] ) ? array() : $instance['columns'];
		$show_team_logo       = empty( $instance['show_team_logo'] ) ? false : $instance['show_team_logo'];
		$show_full_table_link = empty( $instance['show_full_table_link'] ) ? false : $instance['show_full_table_link'];

		do_action( 'sportspress_before_widget', $args, $instance, 'league-table' );
		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		}

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'league-table' );

		sp_get_template(
			'league-table.php',
			array(
				'id'                   => $id,
				'title'                => $caption,
				'number'               => $number,
				'columns'              => $columns,
				'show_full_table_link' => $show_full_table_link,
				'show_team_logo'       => $show_team_logo,
			)
		);

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'league-table' );

		echo wp_kses_post( $after_widget );
		do_action( 'sportspress_after_widget', $args, $instance, 'league-table' );
	}

	function update( $new_instance, $old_instance ) {
		$instance                         = $old_instance;
		$instance['title']                = strip_tags( $new_instance['title'] );
		$instance['id']                   = intval( $new_instance['id'] );
		$instance['caption']              = strip_tags( $new_instance['caption'] );
		$instance['number']               = intval( $new_instance['number'] );
		$instance['columns']              = isset( $new_instance['columns'] ) ? (array) $new_instance['columns'] : array();
		$instance['show_team_logo']   	  = isset( $new_instance['show_team_logo'] ) ? $new_instance['show_team_logo'] : false;
		$instance['show_full_table_link'] = isset( $new_instance['show_full_table_link'] ) ? $new_instance['show_full_table_link'] : false;

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'league-table' );

		return $instance;
	}

	function form( $instance ) {
		$defaults             = apply_filters(
			'sportspress_widget_defaults',
			array(
				'title'                => '',
				'id'                   => '',
				'caption'              => '',
				'number'               => 5,
				'columns'              => null,
				'show_team_logo'       => false,
				'show_full_table_link' => true,
			)
		);
		$instance             = wp_parse_args( (array) $instance, $defaults );
		$title                = strip_tags( $instance['title'] );
		$id                   = intval( $instance['id'] );
		$caption              = strip_tags( $instance['caption'] );
		$number               = intval( $instance['number'] );
		$columns              = $instance['columns'];
		$show_team_logo       = $instance['show_team_logo'];
		$show_full_table_link = $instance['show_full_table_link'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'league-table' );
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'caption' ) ); ?>"><?php esc_attr_e( 'Heading:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'caption' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'caption' ) ); ?>" type="text" value="<?php echo esc_attr( $caption ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php printf( esc_attr__( 'Select %s:', 'sportspress' ), esc_attr__( 'League Table', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type'        => 'sp_table',
			'name'             => $this->get_field_name( 'id' ),
			'id'               => $this->get_field_id( 'id' ),
			'show_option_none' => esc_attr__( '&mdash; Select &mdash;', 'sportspress' ),
			'selected'         => $id,
			'values'           => 'ID',
			'class'            => 'widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ) :
			sp_post_adder( 'sp_table', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of teams to show:', 'sportspress' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3"></p>

		<p class="sp-prefs">
			<?php esc_attr_e( 'Columns:', 'sportspress' ); ?><br>
			<?php
			$args        = array(
				'post_type'      => 'sp_column',
				'numberposts'    => -1,
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			);
			$the_columns = get_posts( $args );

			$field_name = $this->get_field_name( 'columns' ) . '[]';
			$field_id   = $this->get_field_id( 'columns' );
			?>
			<?php foreach ( $the_columns as $column ) : ?>
				<label class="button"><input name="<?php echo esc_attr( $field_name ); ?>" type="checkbox" id="<?php echo esc_attr( $field_id ) . '-' . esc_attr( $column->post_name ); ?>" value="<?php echo esc_attr( $column->post_name ); ?>" 
															  <?php
																if ( $columns === null || in_array( $column->post_name, $columns ) ) :
																	?>
					checked="checked"<?php endif; ?>><?php echo esc_attr( $column->post_title ); ?></label>
			<?php endforeach; ?>
		</p>

		<p><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_team_logo' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_team_logo' ) ); ?>" value="1" <?php checked( $show_team_logo, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_team_logo' ) ); ?>"><?php esc_attr_e( 'Display logos', 'sportspress' ); ?></label><br>

		<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_full_table_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_full_table_link' ) ); ?>" value="1" <?php checked( $show_full_table_link, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_full_table_link' ) ); ?>"><?php esc_attr_e( 'Display link to view full table', 'sportspress' ); ?></label></p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'league-table' );
	}
}

register_widget( 'SP_Widget_League_Table' );
