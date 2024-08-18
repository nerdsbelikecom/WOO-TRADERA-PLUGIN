<?php

class TraderaAuth {
    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function __construct($client_id, $client_secret, $redirect_uri) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
    }

    /**
     * Get access token using the authorization code.
     *
     * @param string $authorization_code The authorization code received from Tradera.
     * @return array The response from Tradera API containing the access token.
     */
    public function get_access_token($authorization_code) {
        $url = 'https://api.tradera.com/oauth2/token';
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $authorization_code,
            'redirect_uri' => $this->redirect_uri,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Handle the callback from Tradera and obtain the access token.
     */
    public function handle_callback() {
        // Check if the authorization code is present in the URL parameters
        if (isset($_GET['code'])) {
            $authorization_code = sanitize_text_field($_GET['code']);
            
            // Get the access token
            $response = $this->get_access_token($authorization_code);
            
            if ($response && isset($response['access_token'])) {
                // Store the access token (you need to implement this method)
                $this->store_access_token($response['access_token']);
                
                // Redirect to a specific page after successful login
                wp_redirect(admin_url('admin.php?page=tradera-settings'));
                exit;
            } else {
                // Handle error
                wp_die('Failed to obtain access token from Tradera.');
            }
        } else {
            // Handle error if no authorization code is present
            wp_die('Authorization code is missing.');
        }
    }

    /**
     * Store the access token in the WordPress options or any other storage method.
     *
     * @param string $token The access token to be stored.
     */
    private function store_access_token($token) {
        // Store the token in WordPress options (or use another storage method)
        update_option('tradera_access_token', $token);
    }
}