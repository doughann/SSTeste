<?php
class Journal2Config {

    public $is_admin;
    public $admin_warnings;
    public $primary_menu;
    public $secondary_menu;
    public $top_modules;

    public $is_https;
    public $base_url;
//    public $image_url;

    public $css_settings = array();
    public $js_settings = array();

    public function __construct($registry) {
        $request = $registry->get('request');
        $config = $registry->get('config');

        /* check if is https request */
        $this->is_https = isset($request->server['HTTPS']) && (($request->server['HTTPS'] == 'on') || ($request->server['HTTPS'] == '1'));

        /* set current store base url */
        $this->base_url = $this->is_https ? $config->get('config_ssl') : $config->get('config_url');

//        if (!$this->base_url) {
//            $this->base_url = $this->is_https ? HTTPS_SERVER : HTTP_SERVER;
//        }
//
//        /* set image url */
//        $this->img_url = $this->base_url . 'image/';
    }

}