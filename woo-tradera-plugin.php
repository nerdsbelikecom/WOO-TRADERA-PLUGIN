<?php
/*
    Plugin Name: Woo Tradera Plugin
    Plugin URI: https://github.com/nerdsbelikecom/wooTraderaPlugin
    Description: Gratis plugin för att integrera WooCommerce med Tradera. Betala inte extra till företag som Sharespine.
    Author: Juan Soto
    Author URI: https://www.linkedin.com/in/juan-soto-83bb8765
    Version: 1.0.0
    Text Domain: woo-tradera
*/

// Om filen öppnas direkt, avbryt.
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Definiera plugin-konstanter
if ( ! defined( 'WOO_TRADERA_PLUGIN_PATH' ) ) {
    define( 'WOO_TRADERA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WOO_TRADERA_PLUGIN_URL' ) ) {
    define( 'WOO_TRADERA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Inkludera de nödvändiga filerna
require_once WOO_TRADERA_PLUGIN_PATH . 'includes/class-woo-tradera-loader.php';
require_once WOO_TRADERA_PLUGIN_PATH . 'admin/class-woo-tradera-admin.php';
require_once WOO_TRADERA_PLUGIN_PATH . 'includes/class-woo-tradera.php';
require_once WOO_TRADERA_PLUGIN_PATH . 'includes/class-woo-tradera-get-access-token.php'; // Inkludera filen för autentisering
require_once WOO_TRADERA_PLUGIN_PATH . 'includes/class-woo-tradera-callback-handler.php';

// Aktiveringskod för pluginet
function activate_woo_tradera_plugin() {
    // Här kan du lägga till aktiveringskod om det behövs
}
register_activation_hook( __FILE__, 'activate_woo_tradera_plugin' );

// Deaktiveringskod för pluginet
function deactivate_woo_tradera_plugin() {
    // Här kan du lägga till deaktiveringskod om det behövs
}
register_deactivation_hook( __FILE__, 'deactivate_woo_tradera_plugin' );

// Kör pluginet
function run_woo_tradera_plugin() {
    $plugin = new Woo_Tradera();
    $plugin->run();
}

// Starta pluginet
run_woo_tradera_plugin();

// Hantera OAuth2 auktorisering och token
function handle_tradera_oauth2() {
    if (isset($_GET['code'])) {
        // Skapa en instans av TraderaAuth
        $tradera_auth = new TraderaAuth('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'YOUR_REDIRECT_URI');
        
        // Hämta åtkomsttoken
        $authorization_code = sanitize_text_field($_GET['code']); // Sanera inmatning
        $token_response = $tradera_auth->get_access_token($authorization_code);
        
        if (isset($token_response['access_token'])) {
            $access_token = $token_response['access_token'];
            // Spara eller använd access token som behövs
        } else {
            // Hantera fel
            error_log('Failed to obtain access token: ' . print_r($token_response, true));
        }
    }
}

// Kalla funktionen vid lämplig tidpunkt
add_action('init', 'handle_tradera_oauth2');