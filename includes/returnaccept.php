<?php
// Inkludera din TraderaAuth-klass
include_once 'includes/class-woo-tradera-callback-handler.php';

// Skapa en instans med dina inställningar
$client_id = get_option('tradera_client_id');
$public_key = get_option('tradera_public_key');
$redirect_uri = 'https://www.example.com/returnaccept.php'; // Din Accept URL

// Se till att klassen TraderaAuth är uppdaterad och korrekt
$tradera_auth = new TraderaAuth($client_id, $public_key, $redirect_uri);

// Hantera callback
$tradera_auth->handle_callback();