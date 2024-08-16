<?php
class Woo_Tradera_Admin {
    public function __construct() {
        // Initializing actions and filters
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
    }

    public function register_hooks() {
        // Register additional admin hooks here
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Tradera Settings', 'woo-tradera-plugin'),
            __('Tradera', 'woo-tradera-plugin'),
            'manage_options',
            'woo-tradera-settings',
            [ $this, 'settings_page' ],
            'dashicons-admin-generic'
        );
    }

    public function settings_page() {
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
        </div>
        <?php
    }

    public function register_settings() {
        // Register settings and fields here
    }
}