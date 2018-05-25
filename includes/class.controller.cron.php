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
            add_action("ja_postviews_mail_week", array($this, "postviews_mail_weekly_report"));
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
                $start = self::start_of_week();
                wp_schedule_event(get_gmt_from_date("next {$start} 00:02:00", "U"), "weekly", "ja_postviews_db_week");
            }
            if(!wp_next_scheduled('ja_postviews_mail_week')){
                $start = self::start_of_week();
                wp_schedule_event(get_gmt_from_date("next {$start} 01:00:00", "U"), "weekly", "ja_postviews_mail_week");
            }
        }

        public static function start_of_week() {
            $start_number = intval(get_option("start_of_week"));
            if ($start_number !== 6) {
                $start_number += 1;
            } else {
                $start_number = 0;
            }
            $start = self::get_start_of_week($start_number);
            return $start;
        }

        public function unregister_postviews_cron() {
            wp_clear_scheduled_hook("ja_postveiw_db_day");
            wp_clear_scheduled_hook("ja_postveiw_db_week");
        }

        public function postveiws_db_day() {
            $args = ja_postviews::get_days_postviews();
            $this->model->update_db_cron_day($args);
        }

        public function postveiws_db_week() {
            $args = ja_postviews::get_weeks_postviews();
            $this->model->update_db_cron_week($args);
        }

        public function postviews_mail_weekly_report() {
            $options = get_option("ja_postviews_options");
            $to = $options['mail'];
            if (!empty($to) || !isset($to)) {
                $to = get_option('admin_email');
            }
            $subject = __("post views report","rng-postviews");
            ob_start();
            extract(array(
                'days_period' => ja_postviews::get_days_period(),
                'days_postviews' => ja_postviews::get_days_postviews(),
                'weeks_postviews' => ja_postviews::get_weeks_postviews(),
                'average_views_per_week' => ja_postviews::get_average_views_per_week()
            ));
            include JA_ADM . "mail-body.php";
            $message = ob_get_clean();
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($to, $subject, $message,$headers);
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

