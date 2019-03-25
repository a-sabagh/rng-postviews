<?php

defined('ABSPATH') || exit;

if (!class_exists("rngja_postviews")) {

    class rngja_postviews {

        /**
         * post views key string
         * @var String 
         */
        public $postviews_key = "ja_postviews";

        /**
         * post views day key in wp_options
         * @var String
         */
        public $post_views_day_key = "ja_postviews_day";

        /**
         * post views week key in wp_options
         * @var String
         */
        public $post_views_week_key = "ja_postviews_week";

        public function __construct() {
            add_action("wp_head", array($this, "set_post_views"));
            add_action("admin_enqueue_scripts", array($this, "localize_postviews_data"));
            $legal_pts = $this->legal_post_types();
            foreach ($legal_pts as $legal_pt) {
                add_filter("manage_{$legal_pt}_posts_columns", array($this, 'add_postviews_posts_column'), 10, 1);
                add_action("manage_{$legal_pt}_posts_custom_column", array($this, 'add_postviews_custom_column'), 10, 2);
            }
            add_action("wp_dashboard_setup", array($this, "add_postviews_dashboard_widget"));
        }

        /**
         * add post views report as Dashboard Widget
         */
        public function add_postviews_dashboard_widget() {
            wp_add_dashboard_widget("ja-postviews", __("Post Views Chart", "rng-postviews"), array($this, "postviews_dashboard_widget"));
        }

        public function get_timezone() {
            $timezone = get_option('timezone_string');
            return (empty($timezone)) ? 'UTC' : $timezone;
        }

        /**
         * adding dashboard widget output function
         */
        public function postviews_dashboard_widget() {
            require_once RNGJA_ADM . 'postviews-dashboard-widget.php';
        }

        /**
         * get legal post type for post views based on settings
         * @return type boolean
         */
        public function legal_post_types() {
            global $rngja_settings;
            $settings = $rngja_settings->settings;
            $active_post_type = (array) $settings['legal_post_type'];
            return $active_post_type;
        }

        /**
         * map all array element to integer and convert empty or null element to 0
         * @param Array $arr
         * @return Array
         */
        public function array_map_intval(&$arr) {
            array_map("intval", $arr);
            foreach ($arr as $key => $value) {
                if (is_null($value) or empty($value)) {
                    $arr[$key] = 0;
                }
            }
            return $arr;
        }

        /**
         * manage_{$legal_pt}_posts_custom_column
         * @param type $columns
         * @return type void
         */
        public function add_postviews_posts_column($columns) {
            return array_merge($columns, array('ja_postviews' => '<span class="dashicons dashicons-visibility"></span>'));
        }

        /**
         * get postviews using post id
         * @param Integer $post_id
         * @return Integer
         */
        public function get_postviews_count($post_id = 0) {
            $postviews = (get_post_meta($post_id, $this->postviews_key, TRUE)) ? get_post_meta($post_id, $this->postviews_key, TRUE) : "0";
            return intval($postviews);
        }

        /**
         * add post views column to post list screen
         * @param Array $column
         * @param Integer $post_id
         */
        public function add_postviews_custom_column($column, $post_id) {
            if ($column !== 'ja_postviews') {
                return;
            }
            $postviews = $this->get_postviews_count($post_id);
            echo $postviews;
        }

        /**
         * check legal post type for post views based on settings
         * @param type $args
         * @return boolean
         */
        public function is_legal_post_veiws($args) {
            extract($args);
            $active_posts_type = $this->legal_post_types();
            if (in_array($post_type, $active_posts_type)) {
                return TRUE;
            }
            return FALSE;
        }

        /**
         * check if legal template for set post view
         * @return Boolean
         */
        public function is_legal_situation() {
            return is_singular() and ! is_admin() and ! is_preview() and ! current_user_can("edit_posts");
        }

        /**
         * set post view and core of plugin
         */
        public function set_post_views() {
            $queried_object = get_queried_object();
            if (!$this->is_legal_situation() or empty($queried_object)) {
                return;
            }
            $post_id = (int) $queried_object->ID;
            $post_type = $queried_object->post_type;
            //restricted post views action
            $args = array('post_type' => $post_type);
            $is_legal_post_views = $this->is_legal_post_veiws($args);
            if (!$is_legal_post_views) {
                return;
            }
            //update post views
            $post_meta = $this->postviews_key;
            $postviews_day_key = $this->post_views_day_key;
            $postviews_week_key = $this->post_views_week_key;
            $option_meta = array(
                'day_views' => "{$postviews_day_key}_first",
                'week_views' => "{$postviews_week_key}_first"
            );
            $this->update_post_views_meta($post_id, $post_meta);
            $this->update_post_views_option($option_meta);
        }

        /**
         * update post views
         * @param type $post_id
         * @param type $meta_key
         */
        private function update_post_views_meta($post_id, $meta_key) {
            $old_post_views = (int) get_post_meta($post_id, $meta_key, TRUE);
            if (!empty($old_post_views)) {
                $new_post_views = intval($old_post_views) + 1;
                update_post_meta($post_id, $meta_key, $new_post_views);
            } else {
                add_post_meta($post_id, $meta_key, 1);
            }
        }

        /**
         * update day views and week views
         * @param type $options_name
         */
        public function update_post_views_option($options_name) {
            extract($options_name);
            //day
            $old_day_views = (int) get_option($day_views);
            if (!empty($old_day_views)) {
                $new_day_views = intval($old_day_views) + 1;
                update_option($day_views, $new_day_views);
            } else {
                update_option($day_views, 1);
            }
            //week
            $old_week_views = (int) get_option($week_views);
            if (!empty($old_week_views)) {
                $new_week_views = intval($old_week_views) + 1;
                update_option($week_views, $new_week_views);
            } else {
                update_option($week_views, 1);
            }
        }

        /**
         * get the date of days in current week 
         * @return type array
         */
        public function get_days_period() {
            $days_pd = array();
            $format = "Y/m/d";
            $timezone = $this->get_timezone();
            $date = new DateTime("now", new DateTimeZone($timezone));
            $interval = new DateInterval("P1D");
            for ($i = 0; $i < 7; $i++) {
                $days_pd[] = $date->format($format);
                $date->sub($interval);
            }
            return $days_pd;
        }

        /**
         * get the date of last four week
         * @return type array
         */
        public function get_weeks_period() {
            $weeks_pd = array();
            $format = "Y/m/d";
            $timezone = $this->get_timezone();
            $week_start = rngja_cron::start_of_week();
            $date = new DateTime("last {$week_start}", new DateTimeZone($timezone));
            $interval = new DateInterval("P7D");
            for ($i = 0; $i < 4; $i++) {
                $weeks_pd[] = $date->format($format);
                $date->sub($interval);
            }
            $this->array_map_intval($weeks_pd);
            return $weeks_pd;
        }

        /**
         * get the postviews of days in current week 
         * @return type array
         */
        public function get_days_postviews() {
            $days_pv = array();
            $postviews_day_key = $this->post_views_day_key;
            $numbers = array(
                '_first', '_second', '_third', '_fourth', '_fifth', '_sixth', '_seventh'
            );

            foreach ($numbers as $number) {
                $day = get_option("{$postviews_day_key}{$number}");
                $days_pv[] = (!empty($day)) ? $day : 0;
            }
            $this->array_map_intval($days_pv);
            return $days_pv;
        }

        /**
         * return avrage of post viewes
         * @return boolean
         */
        public function get_average_views_per_week() {
            $postviews_arr = (array) $this->get_days_postviews();
            if (!is_array($postviews_arr)) {
                return false;
            }
            return bcdiv(array_sum(array_filter($postviews_arr)), 7, 3);
        }

        /**
         * get the date of last four week
         * @return type
         */
        public function get_weeks_postviews() {
            $weeks_pv = array();
            $numbers = array(
                '_first', '_second', '_third', '_fourth'
            );

            foreach ($numbers as $number) {
                $postviews_option = get_option("{$this->post_views_week_key}{$number}");
                $weeks_pv[] = (!empty($postviews_option)) ? $postviews_option : 0;
            }
            $this->array_map_intval($weeks_pv);
            return $weeks_pv;
        }

        /**
         * send data of chart to script.js
         */
        public function localize_postviews_data() {
            $data = array(
                'days_period' => array_reverse($this->get_days_period()),
                'weeks_period' => array_reverse($this->get_weeks_period()),
                'days_postviews' => array_reverse($this->get_days_postviews()),
                'weeks_postviews' => array_reverse($this->get_weeks_postviews())
            );
            wp_localize_script("ja-admin-scripts", "postviews_obj", $data);
        }

    }

}
