<?php
/**
 * SportsPress Product Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Products' ) ) :

/**
 * SP_Settings_Products
 */
class SP_Settings_Products extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'products';
		$this->label = __( 'Products', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'sportspress_sections_' . $this->id, array( $this, 'output_sections' ) );
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''          => __( 'Product Options', 'sportspress' ),
			'inventory' => __( 'Inventory', 'sportspress' )
		);

		return $sections;
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );

 		SP_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		SP_Admin_Settings::save_fields( $settings );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {

		if ( $current_section == 'inventory' ) {

			return apply_filters('sportspress_inventory_settings', array(

				array(	'title' => __( 'Inventory Options', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'inventory_options' ),

				array(
					'title' => __( 'Manage Stock', 'sportspress' ),
					'desc' 		=> __( 'Enable stock management', 'sportspress' ),
					'id' 		=> 'sportspress_manage_stock',
					'default'	=> 'yes',
					'type' 		=> 'checkbox'
				),

				array(
					'title' => __( 'Hold Stock (minutes)', 'sportspress' ),
					'desc' 		=> __( 'Hold stock (for unpaid orders) for x minutes. When this limit is reached, the pending order will be cancelled. Leave blank to disable.', 'sportspress' ),
					'id' 		=> 'sportspress_hold_stock_minutes',
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 0,
						'step' 	=> 1
					),
					'css' 		=> 'width:50px;',
					'default'	=> '60',
					'autoload'  => false
				),

				array(
					'title' => __( 'Notifications', 'sportspress' ),
					'desc' 		=> __( 'Enable low stock notifications', 'sportspress' ),
					'id' 		=> 'sportspress_notify_low_stock',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup' => 'start',
					'autoload'      => false
				),

				array(
					'desc' 		=> __( 'Enable out of stock notifications', 'sportspress' ),
					'id' 		=> 'sportspress_notify_no_stock',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup' => 'end',
					'autoload'      => false
				),

				array(
					'title' => __( 'Notification Recipient', 'sportspress' ),
					'desc' 		=> '',
					'id' 		=> 'sportspress_stock_email_recipient',
					'type' 		=> 'email',
					'default'	=> get_option( 'admin_email' ),
					'autoload'      => false
				),

				array(
					'title' => __( 'Low Stock Threshold', 'sportspress' ),
					'desc' 		=> '',
					'id' 		=> 'sportspress_notify_low_stock_amount',
					'css' 		=> 'width:50px;',
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 0,
						'step' 	=> 1
					),
					'default'	=> '2',
					'autoload'      => false
				),

				array(
					'title' => __( 'Out Of Stock Threshold', 'sportspress' ),
					'desc' 		=> '',
					'id' 		=> 'sportspress_notify_no_stock_amount',
					'css' 		=> 'width:50px;',
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 0,
						'step' 	=> 1
					),
					'default'	=> '0',
					'autoload'      => false
				),

				array(
					'title' => __( 'Out Of Stock Visibility', 'sportspress' ),
					'desc' 		=> __( 'Hide out of stock items from the catalog', 'sportspress' ),
					'id' 		=> 'sportspress_hide_out_of_stock_items',
					'default'	=> 'no',
					'type' 		=> 'checkbox'
				),

				array(
					'title' => __( 'Stock Display Format', 'sportspress' ),
					'desc' 		=> __( 'This controls how stock is displayed on the frontend.', 'sportspress' ),
					'id' 		=> 'sportspress_stock_format',
					'css' 		=> 'min-width:150px;',
					'default'	=> '',
					'type' 		=> 'select',
					'options' => array(
						''  			=> __( 'Always show stock e.g. "12 in stock"', 'sportspress' ),
						'low_amount'	=> __( 'Only show stock when low e.g. "Only 2 left in stock" vs. "In Stock"', 'sportspress' ),
						'no_amount' 	=> __( 'Never show stock amount', 'sportspress' ),
					),
					'desc_tip'	=>  true,
				),

				array( 'type' => 'sectionend', 'id' => 'inventory_options'),

			));

		} else {

			// Get shop page
			$shop_page_id = sp_get_page_id('shop');

			$base_slug = ($shop_page_id > 0 && get_page( $shop_page_id )) ? get_page_uri( $shop_page_id ) : 'shop';

			$sportspress_prepend_shop_page_to_products_warning = '';

			if ( $shop_page_id > 0 && sizeof(get_pages("child_of=$shop_page_id")) > 0 )
				$sportspress_prepend_shop_page_to_products_warning = ' <mark class="notice">' . __( 'Note: The shop page has children - child pages will not work if you enable this option.', 'sportspress' ) . '</mark>';

			return apply_filters( 'sportspress_product_settings', array(

				array(	'title' => __( 'Product Listings', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'catalog_options' ),

				array(
					'title' => __( 'Product Archive / Shop Page', 'sportspress' ),
					'desc' 		=> '<br/>' . sprintf( __( 'The base page can also be used in your <a href="%s">product permalinks</a>.', 'sportspress' ), admin_url( 'options-permalink.php' ) ),
					'id' 		=> 'sportspress_shop_page_id',
					'type' 		=> 'single_select_page',
					'default'	=> '',
					'class'		=> 'chosen_select_nostd',
					'css' 		=> 'min-width:300px;',
					'desc_tip'	=> __( 'This sets the base page of your shop - this is where your product archive will be.', 'sportspress' ),
				),

				array(
					'title' => __( 'Shop Page Display', 'sportspress' ),
					'desc' 		=> __( 'This controls what is shown on the product archive.', 'sportspress' ),
					'id' 		=> 'sportspress_shop_page_display',
					'class'		=> 'chosen_select',
					'css' 		=> 'min-width:300px;',
					'default'	=> '',
					'type' 		=> 'select',
					'options' => array(
						''  			=> __( 'Show products', 'sportspress' ),
						'subcategories' => __( 'Show subcategories', 'sportspress' ),
						'both'   		=> __( 'Show both', 'sportspress' ),
					),
					'desc_tip'	=>  true,
				),

				array(
					'title' => __( 'Default Category Display', 'sportspress' ),
					'desc' 		=> __( 'This controls what is shown on category archives.', 'sportspress' ),
					'id' 		=> 'sportspress_category_archive_display',
					'class'		=> 'chosen_select',
					'css' 		=> 'min-width:300px;',
					'default'	=> '',
					'type' 		=> 'select',
					'options' => array(
						''  			=> __( 'Show products', 'sportspress' ),
						'subcategories' => __( 'Show subcategories', 'sportspress' ),
						'both'   		=> __( 'Show both', 'sportspress' ),
					),
					'desc_tip'	=>  true,
				),

				array(
					'title' => __( 'Default Product Sorting', 'sportspress' ),
					'desc' 		=> __( 'This controls the default sort order of the catalog.', 'sportspress' ),
					'id' 		=> 'sportspress_default_catalog_orderby',
					'class'		=> 'chosen_select',
					'css' 		=> 'min-width:300px;',
					'default'	=> 'title',
					'type' 		=> 'select',
					'options' => apply_filters('sportspress_default_catalog_orderby_options', array(
						'menu_order' => __( 'Default sorting (custom ordering + name)', 'sportspress' ),
						'popularity' => __( 'Popularity (sales)', 'sportspress' ),
						'rating'     => __( 'Average Rating', 'sportspress' ),
						'date'       => __( 'Sort by most recent', 'sportspress' ),
						'price'      => __( 'Sort by price (asc)', 'sportspress' ),
						'price-desc' => __( 'Sort by price (desc)', 'sportspress' ),
					)),
					'desc_tip'	=>  true,
				),

				array(
					'title' => __( 'Add to cart', 'sportspress' ),
					'desc' 		=> __( 'Redirect to the cart page after successful addition', 'sportspress' ),
					'id' 		=> 'sportspress_cart_redirect_after_add',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'start'
				),

				array(
					'desc' 		=> __( 'Enable AJAX add to cart buttons on archives', 'sportspress' ),
					'id' 		=> 'sportspress_enable_ajax_add_to_cart',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'end'
				),

				array( 'type' => 'sectionend', 'id' => 'catalog_options' ),

				array(	'title' => __( 'Product Data', 'sportspress' ), 'type' => 'title', 'id' => 'product_data_options' ),

				array(
					'title' => __( 'Weight Unit', 'sportspress' ),
					'desc' 		=> __( 'This controls what unit you will define weights in.', 'sportspress' ),
					'id' 		=> 'sportspress_weight_unit',
					'class'		=> 'chosen_select',
					'css' 		=> 'min-width:300px;',
					'default'	=> 'kg',
					'type' 		=> 'select',
					'options' => array(
						'kg'  => __( 'kg', 'sportspress' ),
						'g'   => __( 'g', 'sportspress' ),
						'lbs' => __( 'lbs', 'sportspress' ),
						'oz' => __( 'oz', 'sportspress' ),
					),
					'desc_tip'	=>  true,
				),

				array(
					'title' => __( 'Dimensions Unit', 'sportspress' ),
					'desc' 		=> __( 'This controls what unit you will define lengths in.', 'sportspress' ),
					'id' 		=> 'sportspress_dimension_unit',
					'class'		=> 'chosen_select',
					'css' 		=> 'min-width:300px;',
					'default'	=> 'cm',
					'type' 		=> 'select',
					'options' => array(
						'm'  => __( 'm', 'sportspress' ),
						'cm' => __( 'cm', 'sportspress' ),
						'mm' => __( 'mm', 'sportspress' ),
						'in' => __( 'in', 'sportspress' ),
						'yd' => __( 'yd', 'sportspress' ),
					),
					'desc_tip'	=>  true,
				),

				array(
					'title' => __( 'Product Ratings', 'sportspress' ),
					'desc' 		=> __( 'Enable ratings on reviews', 'sportspress' ),
					'id' 		=> 'sportspress_enable_review_rating',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'start',
					'show_if_checked' => 'option',
					'autoload'      => false
				),

				array(
					'desc' 		=> __( 'Ratings are required to leave a review', 'sportspress' ),
					'id' 		=> 'sportspress_review_rating_required',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
					'show_if_checked' => 'yes',
					'autoload'      => false
				),

				array(
					'desc' 		=> __( 'Show "verified owner" label for customer reviews', 'sportspress' ),
					'id' 		=> 'sportspress_review_rating_verification_label',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
					'show_if_checked' => 'yes',
					'autoload'      => false
				),

				array(
					'desc' 		=> __( 'Only allow reviews from "verified owners"', 'sportspress' ),
					'id' 		=> 'sportspress_review_rating_verification_required',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'end',
					'show_if_checked' => 'yes',
					'autoload'      => false
				),

				array( 'type' => 'sectionend', 'id' => 'product_data_options' ),

				array(	'title' => __( 'Product Image Sizes', 'sportspress' ), 'type' => 'title','desc' => sprintf(__( 'These settings affect the actual dimensions of images in your catalog - the display on the front-end will still be affected by CSS styles. After changing these settings you may need to <a href="%s">regenerate your thumbnails</a>.', 'sportspress' ), 'http://wordpress.org/extend/plugins/regenerate-thumbnails/'), 'id' => 'image_options' ),

				array(
					'title' => __( 'Catalog Images', 'sportspress' ),
					'desc' 		=> __( 'This size is usually used in product listings', 'sportspress' ),
					'id' 		=> 'shop_catalog_image_size',
					'css' 		=> '',
					'type' 		=> 'image_width',
					'default'	=> array(
						'width' 	=> '150',
						'height'	=> '150',
						'crop'		=> true
					),
					'desc_tip'	=>  true,
				),

				array(
					'title' => __( 'Single Product Image', 'sportspress' ),
					'desc' 		=> __( 'This is the size used by the main image on the product page.', 'sportspress' ),
					'id' 		=> 'shop_single_image_size',
					'css' 		=> '',
					'type' 		=> 'image_width',
					'default'	=> array(
						'width' 	=> '300',
						'height'	=> '300',
						'crop'		=> 1
					),
					'desc_tip'	=>  true,
				),

				array(
					'title' => __( 'Product Thumbnails', 'sportspress' ),
					'desc' 		=> __( 'This size is usually used for the gallery of images on the product page.', 'sportspress' ),
					'id' 		=> 'shop_thumbnail_image_size',
					'css' 		=> '',
					'type' 		=> 'image_width',
					'default'	=> array(
						'width' 	=> '90',
						'height'	=> '90',
						'crop'		=> 1
					),
					'desc_tip'	=>  true,
				),

				array( 'type' => 'sectionend', 'id' => 'image_options' ),

				array(	'title' => __( 'Downloadable Products', 'sportspress' ), 'type' => 'title', 'id' => 'digital_download_options' ),

				array(
					'title' => __( 'File Download Method', 'sportspress' ),
					'desc' 		=> __( 'Forcing downloads will keep URLs hidden, but some servers may serve large files unreliably. If supported, <code>X-Accel-Redirect</code>/ <code>X-Sendfile</code> can be used to serve downloads instead (server requires <code>mod_xsendfile</code>).', 'sportspress' ),
					'id' 		=> 'sportspress_file_download_method',
					'type' 		=> 'select',
					'class'		=> 'chosen_select',
					'css' 		=> 'min-width:300px;',
					'default'	=> 'force',
					'desc_tip'	=>  true,
					'options' => array(
						'force'  	=> __( 'Force Downloads', 'sportspress' ),
						'xsendfile' => __( 'X-Accel-Redirect/X-Sendfile', 'sportspress' ),
						'redirect'  => __( 'Redirect only', 'sportspress' ),
					),
					'autoload'      => false
				),

				array(
					'title' => __( 'Access Restriction', 'sportspress' ),
					'desc' 		=> __( 'Downloads require login', 'sportspress' ),
					'id' 		=> 'sportspress_downloads_require_login',
					'type' 		=> 'checkbox',
					'default'	=> 'no',
					'desc_tip'	=> __( 'This setting does not apply to guest purchases.', 'sportspress' ),
					'checkboxgroup'		=> 'start',
					'autoload'      => false
				),

				array(
					'desc' 		=> __( 'Grant access to downloadable products after payment', 'sportspress' ),
					'id' 		=> 'sportspress_downloads_grant_access_after_payment',
					'type' 		=> 'checkbox',
					'default'	=> 'yes',
					'desc_tip'	=> __( 'Enable this option to grant access to downloads when orders are "processing", rather than "completed".', 'sportspress' ),
					'checkboxgroup'		=> 'end',
					'autoload'      => false
				),

				array( 'type' => 'sectionend', 'id' => 'digital_download_options' ),

			));
		}
	}
}

endif;

return new SP_Settings_Products();