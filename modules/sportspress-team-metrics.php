<?php
/**
 * Team Metrics
 *
 * @author    ThemeBoy
 * @category  Modules
 * @package   SportsPress/Modules
 * @version   2.7.30
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SportsPress_Team_Metrics' ) ) :

	/**
	 * Main SportsPress Team Metrics Class
	 *
	 * @class SportsPress_Team_Metrics
	 * @version 2.7.30
	 */
	class SportsPress_Team_Metrics {

        /**
		 * Constructor
		 */
		public function __construct() {
			// Define constants
			$this->define_constants();

			// Actions
			add_action( 'init', array( $this, 'register_post_type' ) );
			add_action( 'sportspress_config_page', array( $this, 'sp_team_metrics_config' ), 9 );
			add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );

			// Filters
			add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_filter( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
			add_filter( 'sportspress_config_types', array( $this, 'add_post_type' ) );
			add_filter( 'sportspress_team_details', array( $this, 'team_details' ), 10, 2 );
		}

		/**
		 * Define constants.
		 */
		private function define_constants() {
			if ( ! defined( 'SP_TEAM_METRICS_VERSION' ) ) {
				define( 'SP_TEAM_METRICS_VERSION', '2.7.30' );
			}

			if ( ! defined( 'SP_TEAM_METRICS_URL' ) ) {
				define( 'SP_TEAM_METRICS_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'SP_TEAM_METRICS_DIR' ) ) {
				define( 'SP_TEAM_METRICS_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

        /**
		 * Register event specs post type
		 */
		public static function register_post_type() {
			register_post_type(
				'sp_team_metric',
				apply_filters(
					'sportspress_register_post_type_team_metric',
					array(
						'labels'              => array(
							'name'               => esc_attr__( 'Team Metrics', 'sportspress' ),
							'singular_name'      => esc_attr__( 'Team Metric', 'sportspress' ),
							'add_new_item'       => esc_attr__( 'Add New Team Metric', 'sportspress' ),
							'edit_item'          => esc_attr__( 'Edit Team Metric', 'sportspress' ),
							'new_item'           => esc_attr__( 'New', 'sportspress' ),
							'view_item'          => esc_attr__( 'View', 'sportspress' ),
							'search_items'       => esc_attr__( 'Search', 'sportspress' ),
							'not_found'          => esc_attr__( 'No results found.', 'sportspress' ),
							'not_found_in_trash' => esc_attr__( 'No results found.', 'sportspress' ),
						),
						'public'              => false,
						'show_ui'             => true,
						'capability_type'     => 'sp_config',
						'map_meta_cap'        => true,
						'publicly_queryable'  => false,
						'exclude_from_search' => true,
						'hierarchical'        => false,
						'supports'            => array( 'title', 'page-attributes', 'excerpt' ),
						'has_archive'         => false,
						'show_in_nav_menus'   => false,
						'can_export'          => false,
						'show_in_menu'        => false,
					)
				)
			);
		}

        /**
		 * Add screen ids.
		 *
		 * @return array
		 */
		public function screen_ids( $ids ) {
			return array_merge(
				$ids,
				array(
					'edit-sp_team_metric',
					'sp_team_metric',
				)
			);
		}

		public static function add_post_type( $post_types = array() ) {
			$post_types[] = 'sp_team_metric';
			return $post_types;
		}

        /**
		 * Conditonally load the class and functions only needed when viewing this post type.
		 */
		public function include_post_type_handler() {
			include_once SP()->plugin_path() . '/includes/admin/post-types/class-sp-admin-cpt-team-metric.php';
		}

        /**
		 * Display Team Metrics Table at Config Page
		 *
		 * @return null
		 */
		public function sp_team_metrics_config() {
			?>
        <table class="form-table">
            <tbody>
                <?php
                $args = array(
                    'post_type'      => 'sp_team_metric',
                    'numberposts'    => -1,
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                );
                $data = get_posts( $args );
                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <?php esc_attr_e( 'Team Metrics', 'sportspress' ); ?>
                        <p class="description"><?php esc_attr_e( 'Add more details to a team.', 'sportspress' ); ?></p>
                    </th>
                    <td class="forminp">
                        <table class="widefat sp-admin-config-table">
                            <thead>
                                <tr>
                                    <th scope="col"><?php esc_attr_e( 'Label', 'sportspress' ); ?></th>
                                    <th scope="col"><?php esc_attr_e( 'Variable', 'sportspress' ); ?></th>
                                    <th scope="col"><?php esc_attr_e( 'Description', 'sportspress' ); ?></th>
                                    <th scope="col" class="edit"></th>
                                </tr>
                            </thead>
                            <?php
                            if ( $data ) :
                                $i = 0; foreach ( $data as $row ) :
                                    ?>
                                <tr
                                    <?php
                                    if ( $i % 2 == 0 ) {
                                        echo ' class="alternate"';}
                                    ?>
                                >
                                    <td class="row-title"><?php echo wp_kses_post( $row->post_title ); ?></td>
                                    <td><code><?php echo wp_kses_post( $row->post_name ); ?></code></td>
                                    <td><p class="description"><?php echo wp_kses_post( $row->post_excerpt ); ?></p></td>
                                    <td class="edit"><a class="button" href="<?php echo esc_url( get_edit_post_link( $row->ID ) ); ?>"><?php esc_attr_e( 'Edit', 'sportspress' ); ?></s></td>
                                </tr>
                                    <?php
                                                        $i++;
    endforeach; else :
        ?>
                                <tr class="alternate">
                                    <td colspan="4"><?php esc_attr_e( 'No results found.', 'sportspress' ); ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        <div class="tablenav bottom">
                            <a class="button alignleft" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_team_metric' ) ); ?>"><?php esc_attr_e( 'View All', 'sportspress' ); ?></a>
                            <a class="button button-primary alignright" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=sp_team_metric' ) ); ?>"><?php esc_attr_e( 'Add New', 'sportspress' ); ?></a>
                            <br class="clear">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
			<?php
		}

        /**
		 * Add meta boxes.
		 *
		 * @return array
		 */
		public function add_meta_boxes( $meta_boxes ) {
			$meta_boxes['sp_team_metric']           = array(
				'details' => array(
					'title'    => esc_attr__( 'Team Metrics', 'sportspress' ),
					'save'     => 'SP_Meta_Box_Team_Metrics_Details::save',
					'output'   => 'SP_Meta_Box_Team_Metrics_Details::output',
					'context'  => 'normal',
					'priority' => 'high',
				),
			);
			$meta_boxes['sp_team']['metrics'] = array(
				'title'    => esc_attr__( 'Team Metrics', 'sportspress' ),
				'save'     => 'SP_Meta_Box_Team_Metrics::save',
				'output'   => 'SP_Meta_Box_Team_Metrics::output',
				'context'  => 'side',
				'priority' => 'default',
			);
			return $meta_boxes;
		}

        /**
		 * Add team details.
		 *
		 * @return array
		 */
		public function team_details( $data, $id ) {
			$metrics = $this->team_metrics( $id );
			return array_merge( $data, $metrics );
        }

        /**
         * Returns formatted team metrics
         *
         * @access public
         * @return array
         */
        public function team_metrics( $id, $neg = null ) {
            $metrics       = (array) get_post_meta( $id, 'sp_team_metrics', true );
            $metric_labels = (array) sp_get_var_labels( 'sp_team_metric', $neg, false );
            $data        = array();

            foreach ( $metric_labels as $key => $value ) :
                $metric = sp_array_value( $metrics, $key, null );
                if ( $metric == null ) {
                    continue;
                }
                $data[ $value ] = sp_array_value( $metrics, $key, '&nbsp;' );
            endforeach;
            return $data;
        }

    }

endif;

new SportsPress_Team_Metrics();