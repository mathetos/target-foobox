<?php
/*
Plugin Name: Add Target FooBox to Menu Items
Plugin URL: http://mattcromwell.com
Description: Adds the ability for menu items to target FooBox. 
Version: 0.1
Author: Matt Cromwell
Author URI: http://mattcromwell.com
Contributors: corsonr
Text Domain: targetfoobox
Domain Path: languages
*/

class target_foobox_custom_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// load the plugin translation files
		add_action( 'init', array( $this, 'textdomain' ) );
		
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'tarfbx_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'tarfbx_update_custom_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'tarfbx_edit_walker'), 10, 2 );

	} // end constructor
	
	
	/**
	 * Load the plugin's text domain
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'tarfbx', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function tarfbx_add_custom_nav_fields( $menu_item ) {
	
	    $menu_item->subtitle = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
	    return $menu_item;
	    
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function tarfbx_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	
	    // Check if element is properly sent
	    if ( is_array( $_REQUEST['menu-item-subtitle']) ) {
	        $subtitle_value = $_REQUEST['menu-item-subtitle'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_subtitle', $subtitle_value );
	    }
	    
	}
	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function tarfbx_edit_walker($walker,$menu_id) {
	
	    return 'Target_FooBox_Walker_Menu_Edit';
	    
	}

}

// instantiate plugin's class
$GLOBALS['target_foobox_custom_menu'] = new target_foobox_custom_menu();

if(is_admin){
include_once( 'edit_custom_walker.php' );
include_once( 'custom_walker.php' );
}