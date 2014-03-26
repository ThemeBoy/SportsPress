<?php
/**
 * SportsPress Tax Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Tax' ) ) :

/**
 * SP_Settings_Tax
 */
class SP_Settings_Tax extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'tax';
		$this->label = __( 'Tax', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''         => __( 'Tax Options', 'sportspress' ),
			'standard' => __( 'Standard Rates', 'sportspress' )
		);

		// Get tax classes and display as links
		$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option('sportspress_tax_classes' ) ) ) );

		if ( $tax_classes )
			foreach ( $tax_classes as $class )
				$sections[ sanitize_title( $class ) ] = sprintf( __( '%s Rates', 'sportspress' ), $class );

		return $sections;
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'sportspress_tax_classes' ) ) ) );
		$classes_options = array();
		if ( $tax_classes )
			foreach ( $tax_classes as $class )
				$classes_options[ sanitize_title( $class ) ] = esc_html( $class );

		return apply_filters('sportspress_tax_settings', array(

			array(	'title' => __( 'Tax Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'tax_options' ),

			array(
				'title' => __( 'Enable Taxes', 'sportspress' ),
				'desc' 		=> __( 'Enable taxes and tax calculations', 'sportspress' ),
				'id' 		=> 'sportspress_calc_taxes',
				'default'	=> 'no',
				'type' 		=> 'checkbox'
			),

			array(
				'title' => __( 'Prices Entered With Tax', 'sportspress' ),
				'id' 		=> 'sportspress_prices_include_tax',
				'default'	=> 'no',
				'type' 		=> 'radio',
				'desc_tip'	=>  __( 'This option is important as it will affect how you input prices. Changing it will not update existing products.', 'sportspress' ),
				'options'	=> array(
					'yes' => __( 'Yes, I will enter prices inclusive of tax', 'sportspress' ),
					'no' => __( 'No, I will enter prices exclusive of tax', 'sportspress' )
				),
			),

			array(
				'title'     => __( 'Calculate Tax Based On:', 'sportspress' ),
				'id'        => 'sportspress_tax_based_on',
				'desc_tip'	=>  __( 'This option determines which address is used to calculate tax.', 'sportspress' ),
				'default'   => 'shipping',
				'type'      => 'select',
				'options'   => array(
					'shipping' => __( 'Customer shipping address', 'sportspress' ),
					'billing'  => __( 'Customer billing address', 'sportspress' ),
					'base'     => __( 'Shop base address', 'sportspress' )
				),
			),

			array(
				'title'     => __( 'Default Customer Address:', 'sportspress' ),
				'id'        => 'sportspress_default_customer_address',
				'desc_tip'	=>  __( 'This option determines the customers default address (before they input their own).', 'sportspress' ),
				'default'   => 'base',
				'type'      => 'select',
				'options'   => array(
					''     => __( 'No address', 'sportspress' ),
					'base' => __( 'Shop base address', 'sportspress' ),
				),
			),

			array(
				'title' 		=> __( 'Shipping Tax Class:', 'sportspress' ),
				'desc' 		=> __( 'Optionally control which tax class shipping gets, or leave it so shipping tax is based on the cart items themselves.', 'sportspress' ),
				'id' 		=> 'sportspress_shipping_tax_class',
				'css' 		=> 'min-width:150px;',
				'default'	=> 'title',
				'type' 		=> 'select',
				'options' 	=> array( '' => __( 'Shipping tax class based on cart items', 'sportspress' ), 'standard' => __( 'Standard', 'sportspress' ) ) + $classes_options,
				'desc_tip'	=>  true,
			),

			array(
				'title' => __( 'Rounding', 'sportspress' ),
				'desc' 		=> __( 'Round tax at subtotal level, instead of rounding per line', 'sportspress' ),
				'id' 		=> 'sportspress_tax_round_at_subtotal',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
			),

			array(
				'title' 		=> __( 'Additional Tax Classes', 'sportspress' ),
				'desc' 		=> __( 'List additonal tax classes below (1 per line). This is in addition to the default <code>Standard Rate</code>. Tax classes can be assigned to products.', 'sportspress' ),
				'id' 		=> 'sportspress_tax_classes',
				'css' 		=> 'width:100%; height: 65px;',
				'type' 		=> 'textarea',
				'default'	=> sprintf( __( 'Reduced Rate%sZero Rate', 'sportspress' ), PHP_EOL )
			),

			array(
				'title'   => __( 'Display prices in the shop:', 'sportspress' ),
				'id'      => 'sportspress_tax_display_shop',
				'default' => 'excl',
				'type'    => 'select',
				'options' => array(
					'incl'   => __( 'Including tax', 'sportspress' ),
					'excl'   => __( 'Excluding tax', 'sportspress' ),
				)
			),

			array(
				'title'   => __( 'Price display suffix:', 'sportspress' ),
				'id'      => 'sportspress_price_display_suffix',
				'default' => '',
				'type'    => 'text',
				'desc' 		=> __( 'Define text to show after your product prices. This could be, for example, "inc. Vat" to explain your pricing. You can also have prices substituted here using one of the following: <code>{price_including_tax}, {price_excluding_tax}</code>.', 'sportspress' ),
			),

			array(
				'title'   => __( 'Display prices during cart/checkout:', 'sportspress' ),
				'id'      => 'sportspress_tax_display_cart',
				'default' => 'excl',
				'type'    => 'select',
				'options' => array(
					'incl'   => __( 'Including tax', 'sportspress' ),
					'excl'   => __( 'Excluding tax', 'sportspress' ),
				),
				'autoload'      => false
			),

			array(
				'title'   => __( 'Display tax totals:', 'sportspress' ),
				'id'      => 'sportspress_tax_total_display',
				'default' => 'itemized',
				'type'    => 'select',
				'options' => array(
					'single'     => __( 'As a single total', 'sportspress' ),
					'itemized'   => __( 'Itemized', 'sportspress' ),
				),
				'autoload'      => false
			),

			array( 'type' => 'sectionend', 'id' => 'tax_options' ),

		)); // End tax settings
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option('sportspress_tax_classes' ) ) ) );

		if ( $current_section == 'standard' || in_array( $current_section, array_map( 'sanitize_title', $tax_classes ) ) ) {
 			$this->output_tax_rates();
 		} else {
			$settings = $this->get_settings();

			SP_Admin_Settings::output_fields( $settings );
		}
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section, $wpdb;

		if ( ! $current_section ) {

			$settings = $this->get_settings();
			SP_Admin_Settings::save_fields( $settings );

		} else {

			$this->save_tax_rates();

		}

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_sp_tax_rates_%') OR `option_name` LIKE ('_transient_timeout_sp_tax_rates_%')" );
	}

	/**
	 * Output tax rate tables
	 */
	public function output_tax_rates() {
		global $sportspress, $current_section, $wpdb;

		$page          = ! empty( $_GET['p'] ) ? absint( $_GET['p'] ) : 1;
		$limit         = 100;
		$tax_classes   = array_filter( array_map( 'trim', explode( "\n", get_option('sportspress_tax_classes' ) ) ) );
		$current_class = '';

		foreach( $tax_classes as $class )
			if ( sanitize_title( $class ) == $current_section )
				$current_class = $class;
		?>
		<h3><?php printf( __( 'Tax Rates for the "%s" Class', 'sportspress' ), $current_class ? esc_html( $current_class ) : __( 'Standard', 'sportspress' ) ); ?></h3>
		<p><?php printf( __( 'Define tax rates for countries and states below. <a href="%s">See here</a> for available alpha-2 country codes.', 'sportspress' ), 'http://en.wikipedia.org/wiki/ISO_3166-1#Current_codes' ); ?></p>
		<table class="sp_tax_rates sp_input_table sortable widefat">
			<thead>
				<tr>
					<th class="sort">&nbsp;</th>

					<th width="8%"><?php _e( 'Country&nbsp;Code', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('A 2 digit country code, e.g. US. Leave blank to apply to all.', 'sportspress'); ?>">[?]</span></th>

					<th width="8%"><?php _e( 'State&nbsp;Code', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('A 2 digit state code, e.g. AL. Leave blank to apply to all.', 'sportspress'); ?>">[?]</span></th>

					<th><?php _e( 'ZIP/Postcode', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Postcode for this rule. Semi-colon (;) separate multiple values. Leave blank to apply to all areas. Wildcards (*) can be used. Ranges for numeric postcodes (e.g. 12345-12350) will be expanded into individual postcodes.', 'sportspress'); ?>">[?]</span></th>

					<th><?php _e( 'City', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Cities for this rule. Semi-colon (;) separate multiple values. Leave blank to apply to all cities.', 'sportspress'); ?>">[?]</span></th>

					<th width="8%"><?php _e( 'Rate&nbsp;%', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e( 'Enter a tax rate (percentage) to 4 decimal places.', 'sportspress' ); ?>">[?]</span></th>

					<th width="8%"><?php _e( 'Tax&nbsp;Name', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Enter a name for this tax rate.', 'sportspress'); ?>">[?]</span></th>

					<th width="8%"><?php _e( 'Priority', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Choose a priority for this tax rate. Only 1 matching rate per priority will be used. To define multiple tax rates for a single area you need to specify a different priority per rate.', 'sportspress'); ?>">[?]</span></th>

					<th width="8%"><?php _e( 'Compound', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Choose whether or not this is a compound rate. Compound tax rates are applied on top of other tax rates.', 'sportspress'); ?>">[?]</span></th>

					<th width="8%"><?php _e( 'Shipping', 'sportspress' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Choose whether or not this tax rate also gets applied to shipping.', 'sportspress'); ?>">[?]</span></th>

				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="10">
						<a href="#" class="button plus insert"><?php _e( 'Insert row', 'sportspress' ); ?></a>
						<a href="#" class="button minus remove_tax_rates"><?php _e( 'Remove selected row(s)', 'sportspress' ); ?></a>

						<div class="pagination">
							<?php
								echo str_replace( 'page-numbers', 'page-numbers button', paginate_links( array(
									'base'      => add_query_arg( 'p', '%#%' ),
									'type'      => 'plain',
									'prev_text' => '&laquo;',
									'next_text' => '&raquo;',
									'total'     => ceil( absint( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(tax_rate_id) FROM {$wpdb->prefix}sportspress_tax_rates WHERE tax_rate_class = %s;", sanitize_title( $current_class ) ) ) ) / $limit ),
									'current'   => $page
								) ) );
							?>
						</div>

						<a href="#" download="tax_rates.csv" class="button export"><?php _e( 'Export CSV', 'sportspress' ); ?></a>
						<a href="<?php echo admin_url( 'admin.php?import=sportspress_tax_rate_csv' ); ?>" class="button import"><?php _e( 'Import CSV', 'sportspress' ); ?></a>
					</th>
				</tr>
			</tfoot>
			<tbody id="rates">
				<?php
					$rates = $wpdb->get_results( $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}sportspress_tax_rates
						WHERE tax_rate_class = %s
						ORDER BY tax_rate_order
						LIMIT %d, %d
						" ,
						sanitize_title( $current_class ),
						( $page - 1 ) * $limit,
						$limit
					) );

					foreach ( $rates as $rate ) {
						?>
						<tr>
							<td class="sort"><input type="hidden" class="remove_tax_rate" name="remove_tax_rate[<?php echo $rate->tax_rate_id ?>]" value="0" /></td>

							<td class="country" width="8%">
								<input type="text" value="<?php echo esc_attr( $rate->tax_rate_country ) ?>" placeholder="*" name="tax_rate_country[<?php echo $rate->tax_rate_id ?>]" />
							</td>

							<td class="state" width="8%">
								<input type="text" value="<?php echo esc_attr( $rate->tax_rate_state ) ?>" placeholder="*" name="tax_rate_state[<?php echo $rate->tax_rate_id ?>]" />
							</td>

							<td class="postcode">
								<input type="text" value="<?php
									$locations = $wpdb->get_col( $wpdb->prepare( "SELECT location_code FROM {$wpdb->prefix}sportspress_tax_rate_locations WHERE location_type='postcode' AND tax_rate_id = %d ORDER BY location_code", $rate->tax_rate_id ) );

									echo esc_attr( implode( '; ', $locations ) );
								?>" placeholder="*" data-name="tax_rate_postcode[<?php echo $rate->tax_rate_id ?>]" />
							</td>

							<td class="city">
								<input type="text" value="<?php
									$locations = $wpdb->get_col( $wpdb->prepare( "SELECT location_code FROM {$wpdb->prefix}sportspress_tax_rate_locations WHERE location_type='city' AND tax_rate_id = %d ORDER BY location_code", $rate->tax_rate_id ) );
									echo esc_attr( implode( '; ', $locations ) );
								?>" placeholder="*" data-name="tax_rate_city[<?php echo $rate->tax_rate_id ?>]" />
							</td>

							<td class="rate" width="8%">
								<input type="number" step="any" min="0" value="<?php echo esc_attr( $rate->tax_rate ) ?>" placeholder="0" name="tax_rate[<?php echo $rate->tax_rate_id ?>]" />
							</td>

							<td class="name" width="8%">
								<input type="text" value="<?php echo esc_attr( $rate->tax_rate_name ) ?>" name="tax_rate_name[<?php echo $rate->tax_rate_id ?>]" />
							</td>

							<td class="priority" width="8%">
								<input type="number" step="1" min="1" value="<?php echo esc_attr( $rate->tax_rate_priority ) ?>" name="tax_rate_priority[<?php echo $rate->tax_rate_id ?>]" />
							</td>

							<td class="compound" width="8%">
		    					<input type="checkbox" class="checkbox" name="tax_rate_compound[<?php echo $rate->tax_rate_id ?>]" <?php checked( $rate->tax_rate_compound, '1' ); ?> />
		    				</td>

		    				<td class="apply_to_shipping" width="8%">
		    					<input type="checkbox" class="checkbox" name="tax_rate_shipping[<?php echo $rate->tax_rate_id ?>]" <?php checked($rate->tax_rate_shipping, '1' ); ?> />
		    				</td>
						</tr>
						<?php
					}
				?>
			</tbody>
		</table>
		<script type="text/javascript">
			jQuery( function() {
				jQuery('.sp_tax_rates .remove_tax_rates').click(function() {
					var $tbody = jQuery('.sp_tax_rates').find('tbody');
					if ( $tbody.find('tr.current').size() > 0 ) {
						$current = $tbody.find('tr.current');
						$current.find('input').val('');
						$current.find('input.remove_tax_rate').val('1');

						$current.each(function(){
							if ( jQuery(this).is('.new') )
								jQuery(this).remove();
							else
								jQuery(this).hide();
						});
					} else {
						alert('<?php echo esc_js( __( 'No row(s) selected', 'sportspress' ) ); ?>');
					}
					return false;
				});

				jQuery('.sp_tax_rates .export').click(function() {

					var csv_data = "data:application/csv;charset=utf-8,<?php _e( 'Country Code', 'sportspress' ); ?>,<?php _e( 'State Code', 'sportspress' ); ?>,<?php _e( 'ZIP/Postcode', 'sportspress' ); ?>,<?php _e( 'City', 'sportspress' ); ?>,<?php _e( 'Rate %', 'sportspress' ); ?>,<?php _e( 'Tax Name', 'sportspress' ); ?>,<?php _e( 'Priority', 'sportspress' ); ?>,<?php _e( 'Compound', 'sportspress' ); ?>,<?php _e( 'Shipping', 'sportspress' ); ?>,<?php _e( 'Tax Class', 'sportspress' ); ?>\n";

					jQuery('#rates tr:visible').each(function() {
						var row = '';
						jQuery(this).find('td:not(.sort) input').each(function() {

							if ( jQuery(this).is('.checkbox') ) {

								if ( jQuery(this).is(':checked') ) {
									val = 1;
								} else {
									val = 0;
								}

							} else {

								var val = jQuery(this).val();

								if ( ! val )
									val = jQuery(this).attr('placeholder');
							}

							row = row + val + ',';
						});
						row = row + '<?php echo $current_class; ?>';
						//row.substring( 0, row.length - 1 );
						csv_data = csv_data + row + "\n";
					});

					jQuery(this).attr( 'href', encodeURI( csv_data ) );

					return true;
				});

				jQuery('.sp_tax_rates .insert').click(function() {
					var $tbody = jQuery('.sp_tax_rates').find('tbody');
					var size = $tbody.find('tr').size();
					var code = '<tr class="new">\
							<td class="sort">&nbsp;</td>\
							<td class="country" width="8%">\
								<input type="text" placeholder="*" name="tax_rate_country[new][' + size + ']" />\
							</td>\
							<td class="state" width="8%">\
								<input type="text" placeholder="*" name="tax_rate_state[new][' + size + ']" />\
							</td>\
							<td class="postcode">\
								<input type="text" placeholder="*" name="tax_rate_postcode[new][' + size + ']" />\
							</td>\
							<td class="city">\
								<input type="text" placeholder="*" name="tax_rate_city[new][' + size + ']" />\
							</td>\
							<td class="rate" width="8%">\
								<input type="number" step="any" min="0" placeholder="0" name="tax_rate[new][' + size + ']" />\
							</td>\
							<td class="name" width="8%">\
								<input type="text" name="tax_rate_name[new][' + size + ']" />\
							</td>\
							<td class="priority" width="8%">\
								<input type="number" step="1" min="1" value="1" name="tax_rate_priority[new][' + size + ']" />\
							</td>\
							<td class="compound" width="8%">\
		    					<input type="checkbox" class="checkbox" name="tax_rate_compound[new][' + size + ']" />\
		    				</td>\
		    				<td class="apply_to_shipping" width="8%">\
		    					<input type="checkbox" class="checkbox" name="tax_rate_shipping[new][' + size + ']" checked="checked" />\
		    				</td>\
						</tr>';

					if ( $tbody.find('tr.current').size() > 0 ) {
						$tbody.find('tr.current').after( code );
					} else {
						$tbody.append( code );
					}

					jQuery( "td.country input" ).autocomplete({
			            source: availableCountries,
			            minLength: 3
			        });

			        jQuery( "td.state input" ).autocomplete({
			            source: availableStates,
			            minLength: 3
			        });

					return false;
				});

				jQuery('.sp_tax_rates td.postcode, .sp_tax_rates td.city').find('input').change(function() {
					jQuery(this).attr( 'name', jQuery(this).attr( 'data-name' ) );
				});

				var availableCountries = [<?php
					$countries = array();
					foreach ( SP()->countries->get_allowed_countries() as $value => $label )
						$countries[] = '{ label: "' . $label . '", value: "' . $value . '" }';
					echo implode( ', ', $countries );
				?>];

				var availableStates = [<?php
					$countries = array();
					foreach ( SP()->countries->get_allowed_country_states() as $value => $label )
						foreach ( $label as $code => $state )
							$countries[] = '{ label: "' . $state . '", value: "' . $code . '" }';
					echo implode( ', ', $countries );
				?>];

		        jQuery( "td.country input" ).autocomplete({
		            source: availableCountries,
		            minLength: 3
		        });

		        jQuery( "td.state input" ).autocomplete({
		            source: availableStates,
		            minLength: 3
		        });
			});
		</script>
		<?php
	}

	/**
	 * Save tax rates
	 */
	public function save_tax_rates() {
		global $wpdb, $current_section;

		// Get class
		$tax_classes   = array_filter( array_map( 'trim', explode( "\n", get_option('sportspress_tax_classes' ) ) ) );
		$current_class = '';

		foreach( $tax_classes as $class )
			if ( sanitize_title( $class ) == $current_section )
				$current_class = $class;

		// Get POST data
		$tax_rate_country  = isset( $_POST['tax_rate_country'] ) ? $_POST['tax_rate_country'] : array();
		$tax_rate_state    = isset( $_POST['tax_rate_state'] ) ? $_POST['tax_rate_state'] : array();
		$tax_rate_postcode = isset( $_POST['tax_rate_postcode'] ) ? $_POST['tax_rate_postcode'] : array();
		$tax_rate_city     = isset( $_POST['tax_rate_city'] ) ? $_POST['tax_rate_city'] : array();
		$tax_rate          = isset( $_POST['tax_rate'] ) ? $_POST['tax_rate'] : array();
		$tax_rate_name     = isset( $_POST['tax_rate_name'] ) ? $_POST['tax_rate_name'] : array();
		$tax_rate_priority = isset( $_POST['tax_rate_priority'] ) ? $_POST['tax_rate_priority'] : array();
		$tax_rate_compound = isset( $_POST['tax_rate_compound'] ) ? $_POST['tax_rate_compound'] : array();
		$tax_rate_shipping = isset( $_POST['tax_rate_shipping'] ) ? $_POST['tax_rate_shipping'] : array();

		$i = 0;

		// Loop posted fields
		foreach ( $tax_rate_country as $key => $value ) {

			// new keys are inserted...
			if ( $key == 'new' ) {

				foreach ( $value as $new_key => $new_value ) {

					// Sanitize + format
					$country  = strtoupper( sanitize_text_field( $tax_rate_country[ $key ][ $new_key ] ) );
					$state    = strtoupper( sanitize_text_field( $tax_rate_state[ $key ][ $new_key ] ) );
					$postcode = sanitize_text_field( $tax_rate_postcode[ $key ][ $new_key ] );
					$city     = sanitize_text_field( $tax_rate_city[ $key ][ $new_key ] );
					$rate     = number_format( sanitize_text_field( $tax_rate[ $key ][ $new_key ] ), 4, '.', '' );
					$name     = sanitize_text_field( $tax_rate_name[ $key ][ $new_key ] );
					$priority = absint( sanitize_text_field( $tax_rate_priority[ $key ][ $new_key ] ) );
					$compound = isset( $tax_rate_compound[ $key ][ $new_key ] ) ? 1 : 0;
					$shipping = isset( $tax_rate_shipping[ $key ][ $new_key ] ) ? 1 : 0;

					if ( ! $name )
						$name = __( 'Tax', 'sportspress' );

					if ( $country == '*' )
						$country = '';

					if ( $state == '*' )
						$state = '';

					$wpdb->insert(
						$wpdb->prefix . "sportspress_tax_rates",
						array(
							'tax_rate_country'  => $country,
							'tax_rate_state'    => $state,
							'tax_rate'          => $rate,
							'tax_rate_name'     => $name,
							'tax_rate_priority' => $priority,
							'tax_rate_compound' => $compound,
							'tax_rate_shipping' => $shipping,
							'tax_rate_order'    => $i,
							'tax_rate_class'    => sanitize_title( $current_class )
						)
					);

					$tax_rate_id = $wpdb->insert_id;

					if ( ! empty( $postcode ) ) {
						$postcodes = explode( ';', $postcode );
						$postcodes = array_map( 'strtoupper', array_map( 'sanitize_text_field', $postcodes ) );

						$postcode_query = array();

						foreach( $postcodes as $postcode )
							if ( strstr( $postcode, '-' ) ) {
								$postcode_parts = explode( '-', $postcode );

								if ( is_numeric( $postcode_parts[0] ) && is_numeric( $postcode_parts[1] ) && $postcode_parts[1] > $postcode_parts[0] ) {
									for ( $i = $postcode_parts[0]; $i <= $postcode_parts[1]; $i ++ ) {
										if ( ! $i )
											continue;

										if ( strlen( $i ) < strlen( $postcode_parts[0] ) )
											$i = str_pad( $i, strlen( $postcode_parts[0] ), "0", STR_PAD_LEFT );
										
										$postcode_query[] = "( '" . esc_sql( $i ) . "', $tax_rate_id, 'postcode' )";
									}
								}
							} else {
								if ( $postcode )
									$postcode_query[] = "( '" . esc_sql( $postcode ) . "', $tax_rate_id, 'postcode' )";
							}

						$wpdb->query( "INSERT INTO {$wpdb->prefix}sportspress_tax_rate_locations ( location_code, tax_rate_id, location_type ) VALUES " . implode( ',', $postcode_query ) );
					}

					if ( ! empty( $city ) ) {
						$cities = explode( ';', $city );
						$cities = array_map( 'strtoupper', array_map( 'sanitize_text_field', $cities ) );
						foreach( $cities as $city ) {
							$wpdb->insert(
							$wpdb->prefix . "sportspress_tax_rate_locations",
								array(
									'location_code' => $city,
									'tax_rate_id'   => $tax_rate_id,
									'location_type' => 'city',
								)
							);
						}
					}

					$i++;
				}

			// ...whereas the others are updated
			} else {

				$tax_rate_id = absint( $key );

				if ( $_POST['remove_tax_rate'][ $key ] == 1 ) {
					$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}sportspress_tax_rate_locations WHERE tax_rate_id = %d;", $tax_rate_id ) );
					$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}sportspress_tax_rates WHERE tax_rate_id = %d;", $tax_rate_id ) );
					continue;
				}

				// Sanitize + format
				$country  = strtoupper( sanitize_text_field( $tax_rate_country[ $key ] ) );
				$state    = strtoupper( sanitize_text_field( $tax_rate_state[ $key ] ) );
				$rate     = number_format( (double) sanitize_text_field( $tax_rate[ $key ] ), 4, '.', '' );
				$name     = sanitize_text_field( $tax_rate_name[ $key ] );
				$priority = absint( sanitize_text_field( $tax_rate_priority[ $key ] ) );
				$compound = isset( $tax_rate_compound[ $key ] ) ? 1 : 0;
				$shipping = isset( $tax_rate_shipping[ $key ] ) ? 1 : 0;

				if ( ! $name )
					$name = __( 'Tax', 'sportspress' );

				if ( $country == '*' )
					$country = '';

				if ( $state == '*' )
					$state = '';

				$wpdb->update(
					$wpdb->prefix . "sportspress_tax_rates",
					array(
						'tax_rate_country'  => $country,
						'tax_rate_state'    => $state,
						'tax_rate'          => $rate,
						'tax_rate_name'     => $name,
						'tax_rate_priority' => $priority,
						'tax_rate_compound' => $compound,
						'tax_rate_shipping' => $shipping,
						'tax_rate_order'    => $i,
						'tax_rate_class'    => sanitize_title( $current_class )
					),
					array(
						'tax_rate_id' 		=> $tax_rate_id
					)
				);

				if ( isset( $tax_rate_postcode[ $key ] ) ) {
					// Delete old
					$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}sportspress_tax_rate_locations WHERE tax_rate_id = %d AND location_type = 'postcode';", $tax_rate_id ) );

					// Add changed
					$postcode  = sanitize_text_field( $tax_rate_postcode[ $key ] );
					$postcodes = explode( ';', $postcode );
					$postcodes = array_map( 'strtoupper', array_map( 'sanitize_text_field', $postcodes ) );

					$postcode_query = array();

					foreach( $postcodes as $postcode )
						if ( strstr( $postcode, '-' ) ) {
							$postcode_parts = explode( '-', $postcode );

							if ( is_numeric( $postcode_parts[0] ) && is_numeric( $postcode_parts[1] ) && $postcode_parts[1] > $postcode_parts[0] ) {
								for ( $i = $postcode_parts[0]; $i <= $postcode_parts[1]; $i ++ ) {
									if ( ! $i )
										continue;

									if ( strlen( $i ) < strlen( $postcode_parts[0] ) )
										$i = str_pad( $i, strlen( $postcode_parts[0] ), "0", STR_PAD_LEFT );
									
									$postcode_query[] = "( '" . esc_sql( $i ) . "', $tax_rate_id, 'postcode' )";
								}
							}
						} else {
							if ( $postcode )
								$postcode_query[] = "( '" . esc_sql( $postcode ) . "', $tax_rate_id, 'postcode' )";
						}

					$wpdb->query( "INSERT INTO {$wpdb->prefix}sportspress_tax_rate_locations ( location_code, tax_rate_id, location_type ) VALUES " . implode( ',', $postcode_query ) );

				}

				if ( isset( $tax_rate_city[ $key ] ) ) {
					// Delete old
					$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}sportspress_tax_rate_locations WHERE tax_rate_id = %d AND location_type = 'city';", $tax_rate_id ) );

					// Add changed
					$city   = sanitize_text_field( $tax_rate_city[ $key ] );
					$cities = explode( ';', $city );
					$cities = array_map( 'strtoupper', array_map( 'sanitize_text_field', $cities ) );
					foreach( $cities as $city ) {
						if ( $city ) {
							$wpdb->insert(
							$wpdb->prefix . "sportspress_tax_rate_locations",
								array(
									'location_code' => $city,
									'tax_rate_id'   => $tax_rate_id,
									'location_type' => 'city',
								)
							);
						}
					}
				}

				$i++;
			}
		}
	}

}

endif;

return new SP_Settings_Tax();