<?php

defined('ABSPATH') || exit;

if (!class_exists("rngja_init")) {

    class rngja_init {

        public $version;
        public $slug;

        public function __construct($version, $slug) {
            $this->version = $version;
            $this->slug = $slug;
            add_action('plugins_loaded', array($this, 'add_text_domain'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'public_enqueue_scripts'));
            $this->load_modules();
        }

        /**
         * add text domain for translate files
         */
        public function add_text_domain() {
            load_plugin_textdomain($this->slug, FALSE, RNGJA_PRT . "/languages");
        }

        /**
         * enqueue scripts for public 
         */
        public function public_enqueue_scripts() {
            wp_register_style("ja-papular-post-widg", RNGJA_PDU . "public/assets/css/style.css");
        }

        /**
         * enqueue script to admin panel
         * @param type $hook
         */
        public function admin_enqueue_scripts($hook) {
            if ($hook == "index.php") {
                wp_enqueue_style("ja-admin-style", RNGJA_PDU . "admin/assets/css/style.css");
                wp_enqueue_script("ja-chartjs", RNGJA_PDU . "libraries/chart.js", array(), "", TRUE);
                wp_enqueue_script("ja-admin-scripts", RNGJA_PDU . "admin/assets/js/scripts.js", array("jquery", "ja-chartjs"), "", TRUE);
            }
        }

        /**
         * bootstrap plugin modules
         */
        public function load_modules() {
            require_once 'class.controller.settings.php';
            require_once 'class.controller.postviews.php';
            require_once 'class.controller.cron.php';
            require_once 'widgets/papular-posts.php';
        }

    }

}