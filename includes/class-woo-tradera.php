<?php

class Woo_Tradera {
    protected $loader;
    protected $admin;

    public function __construct() {
        $this->loader = Woo_Tradera_Loader::get_instance();
        $this->admin = new Woo_Tradera_Admin();
    }

    public function run() {
        $this->admin->register_hooks($this->loader);
    }
}