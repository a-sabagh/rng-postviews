<?php

if(!class_exists("ja_init")){
    class ja_init{
        public $version;
        public $slug;
        public function __construct($version,$slug) {
            $this->version = $version;
            $this->slug = $slug;
            add_action('plugins_loaded', array($this, 'add_text_domain'));
            add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts'));
            add_action('wp_enqueue_scripts',array($this,'public_enqueue_scripts'));
            $this->load_modules();
        }
        public function add_text_domain(){
            load_plugin_textdomain($this->slug, FALSE , JA_PRT . "/languages");
        }
        public function public_enqueue_scripts(){
            wp_register_style("ja-papular-post-widg", JA_PDU . "public/assets/css/style.css");
        }
        public function admin_enqueue_scripts($hook){
            if($hook == "index.php"){
                wp_enqueue_style("ja-admin-style", JA_PDU . "admin/assets/css/style.css");
                wp_enqueue_script("ja-chartjs", JA_PDU . "libraries/chart.js", array(), "", TRUE);
                wp_enqueue_script("ja-admin-scripts", JA_PDU . "admin/assets/js/scripts.js",array("jquery","ja-chartjs"),"",TRUE);
            }
        }
        public function load_modules(){
            require_once 'class.controller.settings.php';
            require_once 'class.controller.postviews.php';
            require_once 'class.controller.cron.php';
            require_once 'widgets/init.php';
        }
    }
}