<?php

require_once trailingslashit(__DIR__) . 'class.controller.postviews.php';
require_once trailingslashit(__DIR__) . 'class.model.cron.php';

if (!class_exists("rngja_cron")) {

    class rngja_cron {

        public $model;
        public $post_views;

        public function __construct() {
            $this->model = new rngja_cron_model;
            $this->post_views = new rngja_postviews;
            add_filter('cron_schedules', array($this, "add_postviews_interval"));
            register_activation_hook(JA_FILE, array($this, "register_postviews_cron"));
            add_action("ja_postviews_db_day", array($this, "postviews_db_day"));
            add_action("ja_postviews_db_week", array($this, "postviews_db_week"));
            add_action("ja_postviews_mail_week", array($this, "postviews_mail_weekly_report"));
        }

        /**
         * adding cycle to default wp cycle
         * @param type $schedules
         * @return int
         */
        public function add_postviews_interval($schedules) {
            $schedules['ja_weekly'] = array(
                'interval' => 604800,
                'display' => __('Once Weekly', 'rng-postviews')
            );
            return $schedules;
        }

        /**
         * register schedule event (cron)
         */
        public function register_postviews_cron() {
            if (!wp_next_scheduled('ja_postviews_db_day')) {
                wp_schedule_event(get_gmt_from_date("tomorrow 00:00:01", "U"), "daily", "ja_postviews_db_day");
            }
            if (!wp_next_scheduled('ja_postviews_db_week')) {
                $start = self::start_of_week();
                wp_schedule_event(get_gmt_from_date("next {$start} 00:02:00", "U"), "ja_weekly", "ja_postviews_db_week");
            }
            if (!wp_next_scheduled('ja_postviews_mail_week')) {
                $end = self::end_of_week();
                wp_schedule_event(get_gmt_from_date("next {$end} 23:59:00", "U"), "ja_weekly", "ja_postviews_mail_week");
            }
        }

        /**
         * get start of week finall
         * @return type
         */
        public static function start_of_week() {
            $start_number = intval(get_option("start_of_week"));
            $start = self::get_week_by_int($start_number);
            return $start;
        }

        public static function end_of_week() {
            $start_number = intval(get_option("start_of_week"));
            $end_number = $start_number - 1;
            $end_number = ($end_number < 0)? 6 : $end_number;
            $end = self::get_week_by_int($end_number);
            return $end;
        }

        /**
         * update postviews day
         */
        public function postviews_db_day() {
            $args = $this->post_views->get_days_postviews();
            $this->model->update_db_cron_day($this->post_views->post_views_day_key, $args);
        }

        /**
         * update postviews week
         */
        public function postviews_db_week() {
            $args = $this->post_views->get_weeks_postviews();
            $this->model->update_db_cron_week($this->post_views->post_views_week_key, $args);
        }

        /**
         * send weekly report email
         * @global Object $rngja_settings
         */
        public function postviews_mail_weekly_report() {

            global $rngja_settings;
            $settings = $rngja_settings->settings;
            $to = $settings['mail'];
            $subject = __("post views report", "rng-postviews");
            ob_start();
            extract(array(
                'days_period' => $this->post_views->get_days_period(),
                'days_postviews' => $this->post_views->get_days_postviews(),
                'weeks_postviews' => $this->post_views->get_weeks_postviews(),
                'average_views_per_week' => $this->post_views->get_average_views_per_week()
            ));
            include JA_ADM . "mail-body.php";
            $message = ob_get_clean();
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($to, $subject, $message, $headers);
        }

        /**
         * get start of week from setting panel and reurn day
         * @param type $start
         * @return boolean|string
         */
        private static function get_week_by_int($start) {
            $index = (int) $start;
            $week = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
            return $week[$index];
        }

    }

}
new rngja_cron();
