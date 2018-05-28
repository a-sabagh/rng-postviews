<?php

if (!class_exists("ja_postviews")) {

    class ja_postviews {

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
         * adding dashboard widget function
         */
        public function add_postviews_dashboard_widget() {
            wp_add_dashboard_widget("ja-postviews", __("Post Views Chart", "rng-postviews"), array($this, "postviews_dashboard_widget"));
        }

        /**
         * adding dashboard widget output function
         */
        public function postviews_dashboard_widget() {
            require_once JA_ADM . 'postviews-dashboard-widget.php';
        }

        /**
         * get legal post type for post views based on settings
         * @return type boolean
         */
        private function legal_post_types() {
            $active_post_type = get_option("ja_postviews_options");
            if ($active_post_type == FALSE) {
                return array("post");
            } else {
                return $active_post_type['legal_post_type'];
            }
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
         * 
         * @param type $column
         * @param type $post_id
         */
        public function add_postviews_custom_column($column, $post_id) {
            if ($column == 'ja_postviews') {
                $postviews = (get_post_meta($post_id, "ja_postviews", TRUE)) ? get_post_meta($post_id, "ja_postviews", TRUE) : "0";
                echo $postviews;
            }
        }

        /**
         * check legal post type for post views based on settings
         * @param type $args
         * @return boolean
         */
        private function is_legal_post_veiws($args) {
            extract($args);
            $active_post_type = get_option("ja_postviews_options");
            if ($active_post_type == FALSE) {
                $active_post_type = array("post");
            } else {
                $active_post_type = $active_post_type['legal_post_type'];
            }
            if (in_array($post_type, $active_post_type)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        /**
         * set post view and core of plugin
         * @global type $post
         */
        public function set_post_views() {

            if (is_single() and ! is_admin() and ! is_preview()) {
                global $post;
                $post_id = $post->ID;
                $post_type = $post->post_type;
                //restricted post views action
                $args = array(
                    'post_type' => $post_type
                );
                $is_legal_post_views = $this->is_legal_post_veiws($args);
                if ($is_legal_post_views and ! current_user_can("edit_posts")) {
                    //update post views
                    $post_meta = "ja_postviews";
                    $option_meta = array(
                        'day_views' => 'ja_postviews_day_first',
                        'week_views' => 'ja_postviews_week_first'
                    );
                    $this->update_post_views_meta($post_id, $post_meta);
                    $this->update_post_views_option($option_meta);
                }
            }
        }

        /**
         * update post views
         * @param type $post_id
         * @param type $meta_key
         */
        private function update_post_views_meta($post_id, $meta_key) {
            $old_post_views = get_post_meta($post_id, $meta_key, TRUE);
            if (isset($old_post_views) and ! empty($old_post_views)) {
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
            $old_day_views = get_option($day_views);
            if (isset($old_day_views) and ! empty($old_day_views)) {
                $new_day_views = intval($old_day_views) + 1;
                update_option($day_views, $new_day_views);
            } else {
                update_option($day_views, 1);
            }
            //week
            $old_week_views = get_option($week_views);
            if (isset($old_week_views) and ! empty($old_week_views)) {
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
            $date = new DateTime("now");
            $interval = new DateInterval("P1D");
            $days_pd[] = $date->format($format); //first
            $date->sub($interval);
            $days_pd[] = $date->format($format); //second
            $date->sub($interval);
            $days_pd[] = $date->format($format); //third
            $date->sub($interval);
            $days_pd[] = $date->format($format); //fourth
            $date->sub($interval);
            $days_pd[] = $date->format($format); //fifth
            $date->sub($interval);
            $days_pd[] = $date->format($format); //sixth
            $date->sub($interval);
            $days_pd[] = $date->format($format); //seventh
            return $days_pd;
        }

        /**
         * get the date of last four week
         * @return type array
         */
        public static function get_weeks_period() {
            $weeks_pd = array();
            $format = "Y/m/d";
            $week_start = ja_cron::start_of_week();
            $date = new DateTime("last {$week_start}");
            $interval = new DateInterval("P7D");
            $weeks_pd[] = $date->format($format); //first
            $date->sub($interval);
            $weeks_pd[] = $date->format($format); //second
            $date->sub($interval);
            $weeks_pd[] = $date->format($format); //third
            $date->sub($interval);
            $weeks_pd[] = $date->format($format); //fourth
            return $weeks_pd;
        }

        /**
         * get the postviews of days in current week 
         * @return type array
         */
        public static function get_days_postviews() {
            $days_pv = array();
            $ja_postviews_day_first = get_option("ja_postviews_day_first");
            $days_pv[] = (!empty($ja_postviews_day_first)) ? get_option("ja_postviews_day_first") : 0;
            $ja_postviews_day_second = get_option("ja_postviews_day_second");
            $days_pv[] = (!empty($ja_postviews_day_second)) ? get_option("ja_postviews_day_second") : 0;
            $ja_postviews_day_third = get_option("ja_postviews_day_third");
            $days_pv[] = (!empty($ja_postviews_day_third)) ? get_option("ja_postviews_day_third") : 0;
            $ja_postviews_day_fourth = get_option("ja_postviews_day_fourth");
            $days_pv[] = (!empty($ja_postviews_day_fourth)) ? get_option("ja_postviews_day_fourth") : 0;
            $ja_postviews_day_fifth = get_option("ja_postviews_day_fifth");
            $days_pv[] = (!empty($ja_postviews_day_fifth)) ? get_option("ja_postviews_day_fifth") : 0;
            $ja_postviews_day_sixth = get_option("ja_postviews_day_sixth");
            $days_pv[] = (!empty($ja_postviews_day_sixth)) ? get_option("ja_postviews_day_sixth") : 0;
            $ja_postviews_day_seventh = get_option("ja_postviews_day_seventh");
            $days_pv[] = (!empty($ja_postviews_day_seventh)) ? get_option("ja_postviews_day_seventh") : 0;
            return $days_pv;
        }

        public static function get_average_views_per_week() {
            $postviews_arr = self::get_days_postviews();
            if (is_array($postviews_arr)) {
                return bcdiv(array_sum(array_filter($postviews_arr)), 7, 3);
            } else {
                return FALSE;
            }
        }

        /**
         * get the date of last four week
         * @return type
         */
        public static function get_weeks_postviews() {
            $weeks_pv = array();
            $ja_postviews_week_first = get_option("ja_postviews_week_first");
            $weeks_pv[] = (!empty($ja_postviews_week_first)) ? get_option("ja_postviews_week_first") : 0;
            $ja_postviews_week_second = get_option("ja_postviews_week_second");
            $weeks_pv[] = (!empty($ja_postviews_week_second)) ? get_option("ja_postviews_week_second") : 0;
            $ja_postviews_week_third = get_option("ja_postviews_week_third");
            $weeks_pv[] = (!empty($ja_postviews_week_third)) ? get_option("ja_postviews_week_third") : 0;
            $ja_postviews_week_fourth = get_option("ja_postviews_week_fourth");
            $weeks_pv[] = (!empty($ja_postviews_week_fourth)) ? get_option("ja_postviews_week_fourth") : 0;
            return $weeks_pv;
        }

        /**
         * send data of chart to script.js
         */
        public function localize_postviews_data() {
            $data = array(
                'days_period' => array_reverse(self::get_days_period()),
                'weeks_period' => array_reverse(self::get_weeks_period()),
                'days_postviews' => array_reverse(self::get_days_postviews()),
                'weeks_postviews' => array_reverse(self::get_weeks_postviews())
            );
            wp_localize_script("ja-admin-scripts", "postviews_obj", $data);
        }

        /**
         * developer function get post views
         * @global type $post
         * @return boolean
         */
        public static function get_post_veiws() {
            global $post;
            $post_id = $post->ID;
            $post_type = $post->post_type;
            $meta_key = "ja_postviews";
            $args = array(
                'post_type' => $post_type
            );
            $is_legal_post_views = $this->is_legal_post_veiws($args);
            if ($is_legal_post_views) {
                $post_views = get_post_meta($post_id, $meta_key, TRUE);
                return (isset($post_views) and ! empty($post_views)) ? $post_views : "";
            } else {
                return FALSE;
            }
        }

    }

}
new ja_postviews();
