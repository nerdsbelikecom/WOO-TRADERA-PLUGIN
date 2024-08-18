<?php

class Woo_Tradera_Loader {
    
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        add_action($hook, array($component, $callback), $priority, $accepted_args);
    }

    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        add_filter($hook, array($component, $callback), $priority, $accepted_args);
    }

    // Du kan lägga till fler metoder här om det behövs för att hantera andra typer av hooks
}