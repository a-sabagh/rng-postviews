<?php
include 'class.model.cron.php';
if (!class_exists("ja_cron")) {
        
    class ja_cron {
        
        public $model;

        public function __construct() {
            $this->model = new ja_cron_model();
            register_activation_hook(JA_ACT, array($this, "register_postviews_cron"));
            register_uninstall_hook(JA_FILE, array($this, "unregister_postviews_cron"));
            add_action("ja_postveiw_db_day", array($this, "postveiws_db_day"));
            add_action("ja_postveiw_db_week", array($this, "postveiws_db_week"));
        }

        public function register_postviews_cron() {
            if (!wp_next_scheduled('ja_postveiw_db_day')) {
                wp_schedule_event(get_gmt_from_date(current_time("tomorrow 00:00:00"), "U"), "daily", "ja_postveiw_db_day");
            }
            if (!wp_next_scheduled('ja_postviews_db_week')) {
                $start_number = db2_get_option("start_of_week");
                $start_number += 1;
                $start = $this->get_start_of_week($start_number);
                wp_schedule_event(get_gmt_from_date(strtotime("next {$start} 01:00:00"), "U"), "weekly", "ja_postveiw_db_week");
            }
        }
        
        public function unregister_postviews_cron(){
            wp_clear_scheduled_hook("ja_postveiw_db_day");
            wp_clear_scheduled_hook("ja_postveiw_db_week");
        }

        public function postveiws_db_day() {
            $args = array();
            $args[] = get_option("ja_postviews_day_first");
            $args[] = get_option("ja_postviews_day_second");
            $args[] = get_option("ja_postviews_day_third");
            $args[] = get_option("ja_postviews_day_fourth");
            $args[] = get_option("ja_postviews_day_fifth");
            $args[] = get_option("ja_postviews_day_sixth");
            $args[] = get_option("ja_postviews_day_seventh");
            $this->model->update_db_cron_day($args);
        }

        public function postveiws_db_week() {
            $args = array();
            $args[] = get_option("ja_postviews_week_first");
            $args[] = get_option("ja_postviews_week_second");
            $args[] = get_option("ja_postviews_week_third");
            $args[] = get_option("ja_postviews_week_fourth");
            $this->model->update_db_cron_week($args);
        }

        private function get_start_of_week($start) {
            switch ($start) {
                case '0':
                    return "sunday";
                    break;
                case '1':
                    return "monday";
                    break;
                case '2':
                    return "tuesday";
                    break;
                case '3':
                    return "wednesday";
                    break;
                case '4':
                    return "thursday";
                    break;
                case '5':
                    return "friday";
                    break;
                case '6':
                    return "saturday";
                    break;
                default:
                    return FALSE;
                    break;
            }
        }

    }

}

/*
ja_postviews_day_first 
ja_postviews_day_second 
ja_postviews_day_third 
ja_postviews_day_fourth 
ja_postviews_day_fifth 
ja_postviews_day_sixth 
ja_postviews_day_seventh
************************
ja_postviews_week_first 
ja_postviews_week_second 
ja_postviews_week_third 
ja_postviews_week_fourth 
*/

