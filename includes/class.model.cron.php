<?php

defined('ABSPATH') || exit;

class rngja_cron_model {

    /**
     * update option for days cron
     * @global Object $wpdb
     * @param Array $args
     * @param String $postviews_day_key
     */
    public function update_db_cron_day($postviews_day_key, $args = array()) {
        global $wpdb;
        $wpdb->query("START TRANSACTION");
        $result = array();
        $result[] = ($args[0] !== 0) ? update_option("{$postviews_day_key}_first", 0) : TRUE;
        $result[] = ($args[0] !== $args[1]) ? update_option("{$postviews_day_key}_second", $args[0]) : TRUE;
        $result[] = ($args[1] !== $args[2]) ? update_option("{$postviews_day_key}_third", $args[1]) : TRUE;
        $result[] = ($args[2] !== $args[3]) ? update_option("{$postviews_day_key}_fourth", $args[2]) : TRUE;
        $result[] = ($args[3] !== $args[4]) ? update_option("{$postviews_day_key}_fifth", $args[3]) : TRUE;
        $result[] = ($args[4] !== $args[5]) ? update_option("{$postviews_day_key}_sixth", $args[4]) : TRUE;
        $result[] = ($args[5] !== $args[6]) ? update_option("{$postviews_day_key}_seventh", $args[5]) : TRUE;
        if (in_array(FALSE, $result)) {
            $wpdb->query("ROLLBACK");
        } else {
            $wpdb->query("COMMIT");
        }
    }

    /**
     * update options for week cron
     * @global type $wpdb
     * @param type $args
     * @param String $postviews_week_key
     */
    public function update_db_cron_week($postviews_week_key, $args = array()) {
        global $wpdb;
        $wpdb->query("START TRANSACTION");
        $result = array();
        $result[] = ($args[0] !== 0) ? update_option("{$postviews_week_key}_first", 0) : TRUE;
        $result[] = ($args[0] !== $args[1]) ? update_option("{$postviews_week_key}_second", $args[0]) : TRUE;
        $result[] = ($args[1] !== $args[2]) ? update_option("{$postviews_week_key}_third", $args[1]) : TRUE;
        $result[] = ($args[2] !== $args[3]) ? update_option("{$postviews_week_key}_fourth", $args[2]) : TRUE;
        if (in_array(FALSE, $result)) {
            $wpdb->query("ROLLBACK");
        } else {
            $wpdb->query("COMMIT");
        }
    }

}
