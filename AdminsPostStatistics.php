<?php

/*
  Plugin Name: Admins Post Statistics
  Description: counts views of admin, editor and author posts' also creates sub menu Admin Statistics under Posts to see number of posts they've published
  Version: 1.0.0
  Author: Hind Shalfeh 
  
 */
 
 //Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once (plugin_dir_path(__FILE__).'count-views-column.php');

require_once (plugin_dir_path(__FILE__).'admin-post-count.php');




  

function aps_admin_enqueue_scripts() {
	//this variable checks if the page we're at is under Posts.
	global $pagenow;
	
	if ( ($pagenow == 'edit.php') ) {
		
		//Plugin Main CSS File.
		wp_enqueue_style( 'aps-admin-css', plugins_url( 'css/admin.css', __FILE__ ) );
		wp_enqueue_style('mylocalcss', plugins_url( 'css/localjquery.css', __FILE__ ));
		 
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( 'aps-admin-js', plugins_url( 'js/apsJS.js', __FILE__ ), array('jquery-ui-datepicker'));

		//adds arrows to the calender style
		wp_enqueue_style('jquery-ui-css', plugins_url( 'css/smoothness/jquery-ui.min.css', __FILE__ ));
	}
	
}
//This hook ensures our scripts and styles are only loaded in the admin.
add_action( 'admin_enqueue_scripts', 'aps_admin_enqueue_scripts' );


?>