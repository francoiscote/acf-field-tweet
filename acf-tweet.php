<?php

/*
Plugin Name: Advanced Custom Fields: Tweet
Plugin URI: PLUGIN_URL
Description: DESCRIPTION
Version: 1.0.0
Author: AUTHOR_NAME
Author URI: AUTHOR_URL
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/




// 1. set text domain
// Reference: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
load_plugin_textdomain( 'acf-tweet', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );




// 2. Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function include_field_types_tweet( $version ) {

	include_once('acf-tweet-v5.php');

}

add_action('acf/include_field_types', 'include_field_types_tweet');

?>
