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

        public function admin_menu() {
            add_submenu_page("options-general.php", __("postviews settings", "ja_postviews"), __("postviews", "ja_postviews"), "administrator", "ja_postviews-settings", array($this, "postviews_settings"));
        }

        public function postviews_settings() {
            include JA_ADM . "settings-panel.php";
        }

        public function general_settings_init() {
            register_setting("ja-postviews-settings", "ja_postviews_options");
            add_settings_section("ja-postviews-section-top", __("general settings", "rng-postviews"), array($this, "general_setting_section_top"), "ja-postviews-settings");
            add_settings_field("ja-postviews-pt", __("permission", "rng-postviews"), array($this, "general_setting_legal_post_type"), "ja-postviews-settings", "ja-postviews-section-top", array("id" => "ja-post-type", "name" => "legal_post_type"));
        }

        public function general_setting_section_top() {
            _e("postviews setting page. please at the first select permission for post type");
        }

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

        public function configure_notices() {
            $dismiss = get_option("ja_configration_dissmiss");
            if (!$dismiss) {
                $notice = '<div class="updated"><p>' . __('RNG_postviews is activated, you may need to configure it to work properly.', 'rng-postviews') . ' <a href="' . admin_url('admin.php?page=ja_postviews-settings') . '">' . __('Go to Settings page', 'rng-postviews') . '</a> &ndash; <a href="' . add_query_arg(array('ja_dismiss_notice' => 'true', 'ja_nonce' => wp_create_nonce("ja_dismiss_nonce"))) . '">' . __('Dismiss', 'rng-postviews') . '</a></p></div>';
                echo $notice;
            }
        }

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

        public function add_setting_link($links) {
            $mylinks = array(
                '<a href="' . admin_url('options-general.php?page=ja_postviews-settings') . '">' . __("Settings", "rng-postviews") . '</a>',
            );
            return array_merge($links, $mylinks);
        }

    }

}
new ja_settings();
