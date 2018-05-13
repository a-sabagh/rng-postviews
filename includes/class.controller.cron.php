<?php

if (!class_exists("ja_cron")) {

    class ja_cron {

        public function __construct() {
            register_activation_hook(JA_ACT, array($this, "register_postviews_cron"));
            add_action("ja_postveiw_db_day", array($this, "postveiw_db_day"));
            add_action("ja_postveiw_db_week", array($this, "postveiw_db_week"));
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

        public function postveiw_db_day() {
            $first  = get_option("ja_postviews_day_first");
            $second = get_option("ja_postviews_day_second");
            $third  = get_option("ja_postviews_day_third");
            $fourth = get_option("ja_postviews_day_fourth");
            $fifth  = get_option("ja_postviews_day_fifth");
            $sixth  = get_option("ja_postviews_day_sixth");
            $seventh = get_option("ja_postviews_day_seventh");
            
            
        }

        public function postveiw_db_week() {
            
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

