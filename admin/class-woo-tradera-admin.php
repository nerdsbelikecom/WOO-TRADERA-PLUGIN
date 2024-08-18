<?php

class Woo_Tradera_Admin {
    public function __construct() {
        // Registrera hooks för admin-panelen
        $this->register_hooks();
    }

    public function register_hooks() {
        // Registrera admin-menyn och autentisering och utloggning hooks
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_post_woo_tradera_authenticate', [$this, 'authenticate']);
        add_action('admin_post_woo_tradera_logout', [$this, 'logout']);
        
        // Registrera inställningar
        add_action('admin_init', [$this, 'initialize_settings']);
    }

    public function initialize_settings() {
        // Registrera inställningar
        register_setting('woo_tradera_options_group', 'woo_tradera_api_key');
        register_setting('woo_tradera_options_group', 'woo_tradera_api_secret');

        // Lägg till inställningssektioner
        add_settings_section(
            'woo_tradera_main_section',
            __('API Settings', 'woo-tradera-plugin'),
            null,
            'woo-tradera-settings'
        );

        // Lägg till inställningsfält
        add_settings_field(
            'woo_tradera_api_key',
            __('API Key', 'woo-tradera-plugin'),
            [$this, 'render_api_key_field'],
            'woo-tradera-settings',
            'woo_tradera_main_section'
        );

        add_settings_field(
            'woo_tradera_api_secret',
            __('API Secret', 'woo-tradera-plugin'),
            [$this, 'render_api_secret_field'],
            'woo-tradera-settings',
            'woo_tradera_main_section'
        );
    }

    public function render_api_key_field() {
        $api_key = get_option('woo_tradera_api_key');
        echo '<input type="text" name="woo_tradera_api_key" value="' . esc_attr($api_key) . '" />';
    }

    public function render_api_secret_field() {
        $api_secret = get_option('woo_tradera_api_secret');
        echo '<input type="text" name="woo_tradera_api_secret" value="' . esc_attr($api_secret) . '" />';
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Tradera Settings', 'woo-tradera-plugin'),
            __('Tradera', 'woo-tradera-plugin'),
            'manage_options',
            'woo-tradera-settings',
            [$this, 'settings_page'],
            'dashicons-admin-generic'
        );
    }

    public function settings_page() {
        $current_user = wp_get_current_user();
        $token = get_user_meta($current_user->ID, 'tradera_token', true);
        $userId = get_user_meta($current_user->ID, 'tradera_user_id', true);
        ?>
        <div class="wrap">
            <h1><?php _e('Tradera API Settings', 'woo-tradera-plugin'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('woo_tradera_options_group');
                do_settings_sections('woo-tradera-settings');
                submit_button();
                ?>
            </form>

            <h2><?php _e('Tradera Autentisering', 'woo-tradera-plugin'); ?></h2>
            <?php if ($token && $userId): ?>
                <p><?php printf(__('Du är inloggad som Tradera-användare: %s', 'woo-tradera-plugin'), esc_html($userId)); ?></p>
                <a href="<?php echo esc_url(admin_url('admin-post.php?action=woo_tradera_logout')); ?>" class="button"><?php _e('Logga ut', 'woo-tradera-plugin'); ?></a>
            <?php else: ?>
                <p><?php _e('Du är inte inloggad på Tradera.', 'woo-tradera-plugin'); ?></p>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="woo_tradera_authenticate">
                    <input type="submit" class="button button-primary" value="<?php _e('Logga in på Tradera', 'woo-tradera-plugin'); ?>">
                </form>
            <?php endif; ?>
        </div>
        <?php
    }

    public function authenticate() {
        // Autentisering logik
        $current_user = wp_get_current_user();
        update_user_meta($current_user->ID, 'tradera_token', 'dummy_token');
        update_user_meta($current_user->ID, 'tradera_user_id', 'dummy_user_id');

        wp_redirect(admin_url('admin.php?page=woo-tradera-settings'));
        exit;
    }

    public function logout() {
        $current_user = wp_get_current_user();
        delete_user_meta($current_user->ID, 'tradera_token');
        delete_user_meta($current_user->ID, 'tradera_user_id');

        wp_redirect(admin_url('admin.php?page=woo-tradera-settings'));
        exit;
    }
}