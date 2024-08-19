<?php

class Woo_Tradera_Product_Data_Tabs {
    public function __construct() {
        // Hookar för att lägga till flik och panel
        add_filter('woocommerce_product_data_tabs', [$this, 'add_woo_to_tradera_tab']);
        add_action('woocommerce_product_data_panels', [$this, 'add_woo_to_tradera_panel']);
        // Hookar för att spara fälten
        add_action('woocommerce_process_product_meta', [$this, 'save_woo_to_tradera_fields']);
        // Hookar för att ladda skript för dynamisk fältvisning
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    /**
     * Lägg till en ny flik i produktdata
     *
     * @param array $tabs Existerande flikar
     * @return array Modifierade flikar
     */
    public function add_woo_to_tradera_tab($tabs) {
        $tabs['woo_to_tradera'] = array(
            'label'    => __('Woo to Tradera', 'woo-tradera-plugin'),
            'target'   => 'woo_to_tradera_product_data',
            'class'    => array('show_if_simple'), // Använd denna klass för att styra visningen baserat på produkttyp
        );
        return $tabs;
    }

    /**
     * Lägg till innehållet till den nya fliken
     */
    public function add_woo_to_tradera_panel() {
        global $post;
        ?>
        <div id='woo_to_tradera_product_data' class='panel woocommerce_options_panel'>
            <div class='options_group'>
                <p class='form-field'>
                    <label for='_tradera_integration'><?php _e('Integrera med Tradera', 'woo-tradera-plugin'); ?></label>
                    <input type='checkbox' id='_tradera_integration' name='_tradera_integration' value='1' <?php checked(get_post_meta($post->ID, '_tradera_integration', true), '1'); ?> />
                    <span class='description'><?php _e('Markera om du vill integrera denna produkt med Tradera.', 'woo-tradera-plugin'); ?></span>
                </p>
                <p class='form-field'>
                    <label for='_tradera_title'><?php _e('Rubrik', 'woo-tradera-plugin'); ?></label>
                    <input type='text' class='short' id='_tradera_title' name='_tradera_title' value='<?php echo esc_attr(get_post_meta($post->ID, '_tradera_title', true)); ?>' />
                    <span class='description'><?php _e('Ange rubriken för produkten som ska skickas till Tradera.', 'woo-tradera-plugin'); ?></span>
                </p>
                <p class='form-field'>
                    <label for='_tradera_description'><?php _e('Beskrivning', 'woo-tradera-plugin'); ?></label>
                    <textarea class='short' id='_tradera_description' name='_tradera_description'><?php echo esc_textarea(get_post_meta($post->ID, '_tradera_description', true)); ?></textarea>
                    <span class='description'><?php _e('Ange beskrivningen för produkten som ska skickas till Tradera.', 'woo-tradera-plugin'); ?></span>
                </p>
                <p class='form-field'>
                    <label for='_tradera_category'><?php _e('Kategori', 'woo-tradera-plugin'); ?></label>
                    <select id='_tradera_category' name='_tradera_category'>
                        <?php
                        // Här kan du hämta kategorier från Tradera och fylla i dem
                        $categories = array('Kategori 1', 'Kategori 2', 'Kategori 3'); // Exempelvärden
                        foreach ($categories as $category) {
                            echo '<option value="' . esc_attr($category) . '"' . selected(get_post_meta($post->ID, '_tradera_category', true), $category, false) . '>' . esc_html($category) . '</option>';
                        }
                        ?>
                    </select>
                    <span class='description'><?php _e('Välj en kategori för produkten som ska skickas till Tradera.', 'woo-tradera-plugin'); ?></span>
                </p>
                <p class='form-field'>
                    <label for='_tradera_ad_format'><?php _e('Annonsformat', 'woo-tradera-plugin'); ?></label>
                    <select id='_tradera_ad_format' name='_tradera_ad_format'>
                        <option value='auction' <?php selected(get_post_meta($post->ID, '_tradera_ad_format', true), 'auction'); ?>><?php _e('Auktion', 'woo-tradera-plugin'); ?></option>
                        <option value='buy_now' <?php selected(get_post_meta($post->ID, '_tradera_ad_format', true), 'buy_now'); ?>><?php _e('Köp nu', 'woo-tradera-plugin'); ?></option>
                        <option value='auction_buy_now' <?php selected(get_post_meta($post->ID, '_tradera_ad_format', true), 'auction_buy_now'); ?>><?php _e('Auktion + Köp nu', 'woo-tradera-plugin'); ?></option>
                    </select>
                    <span class='description'><?php _e('Välj annonsformatet för produkten.', 'woo-tradera-plugin'); ?></span>
                </p>
                <div id='tradera_ad_fields'></div>
            </div>
        </div>
        <?php
    }

    /**
     * Spara produktens Tradera-relaterade fält
     *
     * @param int $post_id Produkt-ID
     */
    public function save_woo_to_tradera_fields($post_id) {
        if (isset($_POST['_tradera_integration'])) {
            update_post_meta($post_id, '_tradera_integration', sanitize_text_field($_POST['_tradera_integration']));
        } else {
            delete_post_meta($post_id, '_tradera_integration');
        }

        if (isset($_POST['_tradera_title'])) {
            update_post_meta($post_id, '_tradera_title', sanitize_text_field($_POST['_tradera_title']));
        }
        if (isset($_POST['_tradera_description'])) {
            update_post_meta($post_id, '_tradera_description', sanitize_textarea_field($_POST['_tradera_description']));
        }
        if (isset($_POST['_tradera_category'])) {
            update_post_meta($post_id, '_tradera_category', sanitize_text_field($_POST['_tradera_category']));
        }
        if (isset($_POST['_tradera_ad_format'])) {
            update_post_meta($post_id, '_tradera_ad_format', sanitize_text_field($_POST['_tradera_ad_format']));
        }
        if (isset($_POST['_tradera_auction_price'])) {
            update_post_meta($post_id, '_tradera_auction_price', sanitize_text_field($_POST['_tradera_auction_price']));
        }
        if (isset($_POST['_tradera_auction_end_date'])) {
            update_post_meta($post_id, '_tradera_auction_end_date', sanitize_text_field($_POST['_tradera_auction_end_date']));
        }
        if (isset($_POST['_tradera_auction_end_time'])) {
            update_post_meta($post_id, '_tradera_auction_end_time', sanitize_text_field($_POST['_tradera_auction_end_time']));
        }
        if (isset($_POST['_tradera_buy_now_price'])) {
            update_post_meta($post_id, '_tradera_buy_now_price', sanitize_text_field($_POST['_tradera_buy_now_price']));
        }
    }

    /**
     * Ladda skript för att hantera dynamisk visning av fält
     */
    public function enqueue_admin_scripts() {
        wp_enqueue_script('woo-tradera-admin-script', WOO_TRADERA_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery'), '1.0.0', true);
    }
}

// Instansiera klassen när WooCommerce är laddad
add_action('plugins_loaded', function() {
    new Woo_Tradera_Product_Data_Tabs();
});