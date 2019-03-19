<?php
if (!class_exists("rngja_settings")) {

    class rngja_settings {

        public $settings;

        public function __construct() {
            $this->settings = $this->get_ja_settings();
            if (!is_admin()) {
                return;
            }
            add_action("admin_menu", array($this, "admin_menu"));
            add_action("admin_init", array($this, "general_settings_init"));
            add_action("admin_notices", array($this, "configure_notices"));
            add_action("admin_init", array($this, "dismiss_configuration"));
            add_filter('plugin_action_links_' . JA_PRU, array($this, 'add_setting_link'));
        }

        public function get_ja_settings() {
            $ja_settings_array = array(
                'legal_post_type' => array('post'),
                'mail' => get_option('admin_email')
            );

            $ja_settings = get_option("ja_postviews_options");
            if (empty($ja_settings)) {
                return $ja_settings_array;
            }

            $ja_settings_array['legal_pt'] = (array) $ja_settings['legal_post_type'];
            $ja_settings_array['mail'] = (string) $ja_settings['mail'];
            return $ja_settings_array;
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
            $settings = $this->settings;
            $mail = sanitize_email($settings['mail']);
            ?>
            <input type="text" id="<?php echo $args['id']; ?>" name="<?php echo $args['name']; ?>" value="<?php echo $mail; ?>">
            <?php
        }

        /**
         * output of setting section ja-postviews-section-top
         */
        public function general_setting_section_top() {
            _e("Post views setting page. please at the first select permission for post type", "rng-postviews");
        }

        /**
         * output of setting field ja-postviews-pt
         * @param Array $args
         */
        public function general_setting_legal_post_type($args) {
            $settings = $this->settings;
            $active_post_type = $settings['legal_post_type'];

            $pt_args = array('public' => TRUE);
            $post_types = get_post_types($pt_args, 'names');
            foreach ($post_types as $post_type):
                $checked = '';
                if (is_array($active_post_type)) {
                    $checked = (in_array($post_type, $active_post_type)) ? "checked" : "";
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
            if ($dismiss) {
                return;
            }
            $notice = '<div class="updated"><p>' . __('rng-postviewes is activated, you may need to configure it to work properly.', 'rng-postviews') . ' <a href="' . admin_url('admin.php?page=ja_postviews-settings') . '">' . __('Go to Settings page', 'rng-postviews') . '</a> &ndash; <a href="' . add_query_arg(array('ja_dismiss_notice' => 'true', 'ja_nonce' => wp_create_nonce("ja_dismiss_nonce"))) . '">' . __('Dismiss', 'rng-postviews') . '</a></p></div>';
            echo $notice;
        }

        /**
         * check if configation dismiss or not
         * @param String $verify_nonce
         * @param String $dismiss_notice
         * @param String $dismiss
         * @param String $page
         * @return boolean
         */
        public function check_dismiss_configuration($verify_nonce, $dismiss_notice, $dismiss, $page) {
            if ((isset($verify_nonce, $dismiss, $dismiss_notice) and $dismiss == "true") or $page == "ja_postviews-settings") {
                return true;
            }
            return false;
        }

        /**
         * dismiss configuration notice
         */
        public function dismiss_configuration() {
            $verify_nonce = wp_verify_nonce($_GET['ja_nonce'], 'ja_dismiss_nonce');
            $dismiss_notice = $_GET['ja_dismiss_notice'];
            $dismiss = $_GET['ja_dismiss'];
            $page = $_GET['page'];
            if ($this->check_dismiss_configuration($verify_nonce, $dismiss_notice, $dismiss, $page)) {
                update_option("ja_configration_dissmiss", 1);
            }
        }

        /**
         * adding setting link to rng-postviewes in plugin list
         * @param Array $links
         * @return Array
         */
        public function add_setting_link($links) {
            $mylinks = array(
                '<a href="' . admin_url('options-general.php?page=ja_postviews-settings') . '">' . __("Settings", "rng-postviews") . '</a>',
            );
            return array_merge($links, $mylinks);
        }

    }

}
global $rngja_postviewes;
$rngja_postviewes = new rngja_settings();
