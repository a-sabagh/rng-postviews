<?php
if (!class_exists("ja_settings")) {

    class ja_settings {

        public function __construct() {
            add_action("admin_menu", array($this, "admin_menu"));
            add_action("admin_init", array($this, "general_settings_init"));
            //new 
            add_action("admin_notices", array($this, "configure_notices"));
            add_action("admin_init", array($this, "dismiss_configuration"));
            add_filter('plugin_action_links_' . JA_PRU, array($this, 'add_setting_link'));
        }

        /**
         * adding general setting of postviews plugin to admin menu
         */
        public function admin_menu() {
            add_submenu_page("options-general.php", __("Postviews Settings", "rng-postviews"), __("postviews", "rng-postviews"), "administrator", "ja_postviews-settings", array($this, "postviews_settings"));
        }

        /**
         * output of setting page for postviews options
         */
        public function postviews_settings() {
            include JA_ADM . "settings-panel.php";
        }

        /**
         * register setting and section and fields
         */
        public function general_settings_init() {
            register_setting("ja-postviews-settings", "ja_postviews_options");
            add_settings_section("ja-postviews-section-top", __("General settings", "rng-postviews"), array($this, "general_setting_section_top"), "ja-postviews-settings");
            add_settings_field("ja-postviews-pt", __("Permission", "rng-postviews"), array($this, "general_setting_legal_post_type"), "ja-postviews-settings", "ja-postviews-section-top", array("id" => "ja-post-type", "name" => "legal_post_type"));
            add_settings_field("ja-postviews-mail", __("Email Address"), array($this, "general_setting_email"), "ja-postviews-settings", "ja-postviews-section-top", array("id" => "ja-mail", "name" => "mail"));
        }

        /**
         * output of setting field ja-postviews-mail
         * @param type $args
         */
        public function general_setting_email($args) {
            $options = get_option("ja_postviews_options");
            $mail = $options['mail'];
            if (!empty($mail) || !isset($mail)) {
                $mail = get_option('admin_email');
            }
            ?>
            <input type="text" id="<?php echo $args['id']; ?>" name="<?php echo $args['name']; ?>" value="<?php echo $mail; ?>">
            <?php
        }

        /**
         * output of setting section ja-postviews-section-top
         */
        public function general_setting_section_top() {
            _e("postviews setting page. please at the first select permission for post type","rng-postviews");
        }

        /**
         * output of setting field ja-postviews-pt
         * @param type $args
         */
        public function general_setting_legal_post_type($args) {
            $active_post_type = get_option("ja_postviews_options");
            if ($active_post_type == FALSE) {
                $active_post_type = array("post");
            } else {
                $active_post_type = $active_post_type['legal_post_type'];
            }
            $pt_args = array('public' => TRUE);
            $post_types = get_post_types($pt_args, 'names');
            foreach ($post_types as $post_type):
                if (is_array($active_post_type)) {
                    $checked = (in_array($post_type, $active_post_type)) ? "checked" : "";
                } else {
                    $checked = '';
                }
                ?>
                <label>
                <?php echo $post_type ?>&nbsp;<input id="<?php echo $args['id']; ?>" type="checkbox" name="ja_postviews_options[<?php echo $args['name']; ?>][]" <?php echo $checked; ?> value="<?php echo $post_type; ?>" >
                </label>
                <br>
                <?php
            endforeach;
        }

        /**
         * display configuration notice to admin notice after active plugin
         */
        public function configure_notices() {
            $dismiss = get_option("ja_configration_dissmiss");
            if (!$dismiss) {
                $notice = '<div class="updated"><p>' . __('RNG_postviews is activated, you may need to configure it to work properly.', 'rng-postviews') . ' <a href="' . admin_url('admin.php?page=ja_postviews-settings') . '">' . __('Go to Settings page', 'rng-postviews') . '</a> &ndash; <a href="' . add_query_arg(array('ja_dismiss_notice' => 'true', 'ja_nonce' => wp_create_nonce("ja_dismiss_nonce"))) . '">' . __('Dismiss', 'rng-postviews') . '</a></p></div>';
                echo $notice;
            }
        }

        /**
         * dismiss configuration notice is adding in ja_settings->configure_notices()
         */
        public function dismiss_configuration() {
            if (isset($_GET['ja_dismiss_notice']) and $_GET['ja_dismiss'] = 'true' and ( isset($_GET['ja_nonce']))) {
                $verify_nonce = wp_verify_nonce($_GET['ja_nonce'], 'ja_dismiss_nonce');
                if ($verify_nonce) {
                    update_option("ja_configration_dissmiss", 1);
                }
            } elseif (isset($_GET['page']) and $_GET['page'] == "ja_postviews-settings") {
                update_option("ja_configration_dissmiss", 1);
            }
        }

        /**
         * adding setting link to RNG_Postviews in plugin list
         * @param type $links
         * @return type array
         */
        public function add_setting_link($links) {
            $mylinks = array(
                '<a href="' . admin_url('options-general.php?page=ja_postviews-settings') . '">' . __("Settings", "rng-postviews") . '</a>',
            );
            return array_merge($links, $mylinks);
        }

    }

}
new ja_settings();
