<?php

if (!class_exists("ja_postviews")) {

    class ja_postviews {

        public function __construct() {
            add_action("wp_head", array($this, "set_post_views"));
            $legal_pts = $this->legal_post_types();
            foreach ($legal_pts as $legal_pt) {
                add_filter("manage_{$legal_pt}_posts_columns", array($this, 'add_postviews_posts_column'), 10, 1);
                add_action("manage_{$legal_pt}_posts_custom_column", array($this, 'add_postviews_custom_column'), 10, 2);
            }
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
            
            if (is_single() and ! is_admin() and !is_preview()) {
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
        private function update_post_views($post_id, $meta_key) {
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
