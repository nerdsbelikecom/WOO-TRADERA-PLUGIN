<?php
class Woo_Tradera_Loader {
    public static function get_instance() {
        spl_autoload_register( [ __CLASS__, 'autoload' ] );
    }

    private static function autoload( $class_name ) {
        if ( false === strpos( $class_name, 'Woo_Tradera' ) ) {
            return;
        }

        $class_name = str_replace( 'Woo_Tradera\\', '', $class_name );
        $class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );

        $file = WOO_TRADERA_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . $class_name . '.php';

        if ( file_exists( $file ) ) {
            require_once $file;
        }
    }
}