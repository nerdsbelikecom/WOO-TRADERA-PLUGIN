<?php

function woo_tradera_login_page() {
    $appId = 'YOUR_APP_ID'; // Din applikations-ID
    $publicKey = 'YOUR_PUBLIC_KEY'; // Din offentliga nyckel
    $secretKey = wp_generate_uuid4(); // Skapar en unik nyckel för sessionen
    $redirectUri = 'https://yourwebsite.com/tradera-login-callback'; // Din AcceptURL

    $loginUrl = "https://api.tradera.com/tokenlogin.aspx?appId={$appId}&pkey={$publicKey}&skey={$secretKey}&ruparams=" . urlencode("redirect={$redirectUri}");

    // Redirect till Tradera inloggningssida
    wp_redirect($loginUrl);
    exit;
}

// Lägg till denna funktion till en shortcode eller hooka den till en knapp
add_shortcode('woo_tradera_login', 'woo_tradera_login_page');

function woo_tradera_login_callback() {
    if (isset($_GET['token']) && isset($_GET['userId'])) {
        $token = sanitize_text_field($_GET['token']);
        $userId = sanitize_text_field($_GET['userId']);

        // Spara token och userId i användarens meta-data
        $current_user = wp_get_current_user();
        update_user_meta($current_user->ID, 'tradera_token', $token);
        update_user_meta($current_user->ID, 'tradera_user_id', $userId);

        // Bekräftelse till användaren
        echo "Inloggning lyckades! Du kan nu använda pluginet.";
    } else {
        echo "Inloggningen misslyckades eller avbröts.";
    }
}

// Skapa en sida i WordPress som använder denna callback
add_shortcode('woo_tradera_login_callback', 'woo_tradera_login_callback');

function woo_tradera_make_api_call() {
    $current_user = wp_get_current_user();
    $token = get_user_meta($current_user->ID, 'tradera_token', true);
    $userId = get_user_meta($current_user->ID, 'tradera_user_id', true);

    if ($token && $userId) {
        // Här gör du ditt API-anrop
        $appId = 'YOUR_APP_ID'; // Din applikations-ID
        $serviceUrl = "https://api.tradera.com/RestrictedService.svc?appId={$appId}&userId={$userId}&token={$token}";

        // Exempel på att hämta användarens auktioner
        $response = wp_remote_get($serviceUrl);
        
        if (is_wp_error($response)) {
            echo "API-anrop misslyckades.";
        } else {
            $body = wp_remote_retrieve_body($response);
            // Hantera API-svaret här
            echo "API-anrop lyckades: " . esc_html($body);
        }
    } else {
        echo "Du måste vara inloggad via Tradera för att använda denna funktion.";
    }
}

function woo_tradera_admin_menu() {
    add_menu_page('Tradera Login', 'Tradera Login', 'manage_options', 'woo-tradera-admin', 'woo_tradera_admin_page');
}

function woo_tradera_admin_page() {
    $current_user = wp_get_current_user();
    $token = get_user_meta($current_user->ID, 'tradera_token', true);
    $userId = get_user_meta($current_user->ID, 'tradera_user_id', true);

    echo '<h1>Tradera Autentisering</h1>';
    if ($token && $userId) {
        echo "<p>Du är inloggad som Tradera-användare: {$userId}</p>";
        echo '<a href="' . esc_url(admin_url('admin-post.php?action=woo_tradera_logout')) . '">Logga ut</a>';
    } else {
        echo '<p>Du är inte inloggad på Tradera.</p>';
    }
}

function woo_tradera_logout() {
    $current_user = wp_get_current_user();
    delete_user_meta($current_user->ID, 'tradera_token');
    delete_user_meta($current_user->ID, 'tradera_user_id');

    wp_redirect(admin_url('admin.php?page=woo-tradera-admin'));
    exit;
}

add_action('admin_menu', 'woo_tradera_admin_menu');
add_action('admin_post_woo_tradera_logout', 'woo_tradera_logout');

?>