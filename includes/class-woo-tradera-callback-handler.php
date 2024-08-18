<?php
add_action('init', 'handle_tradera_callback');

function handle_tradera_callback() {
    if (isset($_GET['page']) && $_GET['page'] === 'tradera-callback') {
        $tradera_auth = new TraderaAuth('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'YOUR_REDIRECT_URI');
        $tradera_auth->handle_callback();
    }
}