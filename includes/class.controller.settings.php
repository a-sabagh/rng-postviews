<?php

if(!class_exists("ja_settings")){
    class ja_settings{
        public function __construct(){
            add_action("admin_menu", array($this, "admin_menu"));
        }
        public function admin_menu(){
            add_submenu_page("options-general.php", __("postviews settings","ja_postviews"), __("postviews" , "ja_postviews"), "administrator", "ja_postviews-settings", array($this,"postviews_settings"));
        }
        public function postviews_settings(){
            include JA_ADM . "settings-panel.php";
        }
    }
}
new ja_settings();
