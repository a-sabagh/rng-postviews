<?php

include 'class.model.cron.php';
if (!class_exists("ja_cron")) {

    class ja_cron {

        public $model;

        public function __construct() {
            $this->model = new ja_cron_model();
            add_filter('cron_schedules', array($this, "add_postviews_interval"));
            register_activation_hook(JA_FILE, array($this, "register_postviews_cron"));
            register_uninstall_hook(JA_FILE, array($this, "unregister_postviews_cron"));
            add_action("ja_postviews_db_day", array($this, "postveiws_db_day"));
            add_action("ja_postviews_db_week", array($this, "postveiws_db_week"));
        }

        public function add_postviews_interval($schedules) {
            $schedules['weekly'] = array(
                'interval' => 604800,
                'display' => __('Once Weekly')
            );
            return $schedules;
        }

        public function register_postviews_cron() {
            if (!wp_next_scheduled('ja_postveiw_db_day')) {
                wp_schedule_event(get_gmt_from_date("tomorrow 00:00:00", "U"), "daily", "ja_postviews_db_day");
            }
            if (!wp_next_scheduled('ja_postviews_db_week')) {
                $start_number = intval(get_option("start_of_week"));
                if ($start_number !== 6) {
                    $start_number += 1;
                } else {
                    $start_number = 0;
                }
                $start = $this->get_start_of_week($start_number);
                wp_schedule_event(get_gmt_from_date("next {$start} 01:00:00", "U"), "weekly", "ja_postviews_db_week");
            }
        }

        public function unregister_postviews_cron() {
            wp_clear_scheduled_hook("ja_postveiw_db_day");
            wp_clear_scheduled_hook("ja_postveiw_db_week");
        }

        public function postveiws_db_day() {
            $args = array();
            $args[] = (!empty(get_option("ja_postviews_day_first"))) ? get_option("ja_postviews_day_first") : 0;
            $args[] = (!empty(get_option("ja_postviews_day_second"))) ? get_option("ja_postviews_day_second") : 0;
            $args[] = (!empty(get_option("ja_postviews_day_third"))) ? get_option("ja_postviews_day_third") : 0;
            $args[] = (!empty(get_option("ja_postviews_day_fourth"))) ? get_option("ja_postviews_day_fourth") : 0;
            $args[] = (!empty(get_option("ja_postviews_day_fifth"))) ? get_option("ja_postviews_day_fifth") : 0;
            $args[] = (!empty(get_option("ja_postviews_day_sixth"))) ? get_option("ja_postviews_day_sixth") : 0;
            $args[] = (!empty(get_option("ja_postviews_day_seventh"))) ? get_option("ja_postviews_day_seventh") : 0;
            ob_start();
            var_dump($args);
            $output = ob_get_clean();
            error_clear_last();
            error_log($output);
            $this->model->update_db_cron_day($args);
        }

        public function postveiws_db_week() {
            $args = array();
            $args[] = (!empty(get_option("ja_postviews_week_first")))? get_option("ja_postviews_week_first")  : 0;
            $args[] = (!empty(get_option("ja_postviews_week_second")))? get_option("ja_postviews_week_second") : 0;
            $args[] = (!empty(get_option("ja_postviews_week_third")))? get_option("ja_postviews_week_third") : 0;
            $args[] = (!empty(get_option("ja_postviews_week_fourth")))? get_option("ja_postviews_week_fourth") : 0;
            $this->model->update_db_cron_week($args);
        }

        private function get_start_of_week($start) {
            switch ($start) {
                case 0:
                    return "sunday";
                    break;
                case 1:
                    return "monday";
                    break;
                case 2:
                    return "tuesday";
                    break;
                case 3:
                    return "wednesday";
                    break;
                case 4:
                    return "thursday";
                    break;
                case 5:
                    return "friday";
                    break;
                case 6:
                    return "saturday";
                    break;
                default:
                    return FALSE;
                    break;
            }
        }

    }

}
new ja_cron();
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

