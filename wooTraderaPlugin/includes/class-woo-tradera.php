<?php
class Woo_Tradera {
    protected $loader;
    protected $admin;
    protected $public;

    public function __construct() {
        $this->loader = new Woo_Tradera_Loader();
        $this->loader->get_instance();

        $this->admin = new Woo_Tradera_Admin();
        $this->public = new Woo_Tradera_Public();
    }

    public function run() {
        $this->admin->register_hooks();
        $this->public->register_hooks();
    }
}
