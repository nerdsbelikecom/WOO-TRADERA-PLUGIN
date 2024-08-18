<?php

class TraderaAuth {
    private $app_id;
    private $public_key;
    private $redirect_uri;

    public function __construct($app_id, $public_key, $redirect_uri) {
        $this->app_id = $app_id;
        $this->public_key = $public_key;
        $this->redirect_uri = $redirect_uri;
    }

    public function get_authorization_url() {
        $url = 'https://api.tradera.com/tokenlogin.aspx';
        $params = array(
            'appId' => $this->app_id,
            'pkey' => $this->public_key,
            'redirect_uri' => $this->redirect_uri
        );

        return $url . '?' . http_build_query($params);
    }

    public function get_access_token($authorization_code) {
        $url = 'https://api.tradera.com/oauth2/token';
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $authorization_code,
            'redirect_uri' => $this->redirect_uri,
            'appId' => $this->app_id,
            'pkey' => $this->public_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function handle_callback() {
        if (isset($_GET['code'])) {
            $authorization_code = sanitize_text_field($_GET['code']);
            $response = $this->get_access_token($authorization_code);

            if ($response && isset($response['access_token'])) {
                $this->store_access_token($response['access_token']);
                wp_redirect(admin_url('admin.php?page=tradera-settings'));
                exit;
            } else {
                wp_die('Failed to obtain access token from Tradera.');
            }
        } else {
            wp_die('Authorization code is missing.');
        }
    }

    private function store_access_token($token) {
        update_option('tradera_access_token', $token);
    }
}