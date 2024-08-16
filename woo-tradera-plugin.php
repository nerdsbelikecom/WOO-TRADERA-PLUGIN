<?php
/*
    Plugin Name: wooTraderaPlugin
    Plugin URI: https://example.com/woo-tradera-plugin
    Description: A plugin to integrate WooCommerce with Tradera.
    Author: Your Name
    Author URI: https://example.com
    Version: 1.0.0
    Text Domain: woo-tradera-plugin
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define plugin path and URL
if ( ! defined( 'WOO_TRADERA_PLUGIN_PATH' ) ) {
    define( 'WOO_TRADERA_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}
if ( ! defined( 'WOO_TRADERA_PLUGIN_URL' ) ) {
    define( 'WOO_TRADERA_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

// Include required files
require_once WOO_TRADERA_PLUGIN_PATH . 'includes/class-woo-tradera-loader.php';
require_once WOO_TRADERA_PLUGIN_PATH . 'includes/class-woo-tradera.php';

// Register activation and deactivation hooks
function activate_woo_tradera_plugin() {
    // Activation code here
}
register_activation_hook( __FILE__, 'activate_woo_tradera_plugin' );

function deactivate_woo_tradera_plugin() {
    // Deactivation code here
}
register_deactivation_hook( __FILE__, 'deactivate_woo_tradera_plugin' );

// Initialize the plugin
function run_woo_tradera_plugin() {
    $plugin = new Woo_Tradera();
    $plugin->run();
}
run_woo_tradera_plugin();