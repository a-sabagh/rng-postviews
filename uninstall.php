<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
//delte options
$options = array(
    //setting option
    "ja_postviews_options",
    //configuration option
    "ja_configration_dissmiss",
    //cron options
    'ja_postviews_day_first',
    'ja_postviews_day_second',
    'ja_postviews_day_third',
    'ja_postviews_day_fourth',
    'ja_postviews_day_fifth',
    'ja_postviews_day_sixth',
    'ja_postviews_day_seventh',
    'ja_postviews_week_first',
    'ja_postviews_week_second',
    'ja_postviews_week_third',
    'ja_postviews_week_fourth',
    //widgets
    'widget_ja_papular_posts'
);
foreach ($options as $option) {
    if (get_option($option)) {
        delete_option($option);
    }
}
// drop a metadata
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = 'ja_postviews'");

//clear crones
if (wp_next_scheduled('ja_postviews_db_day')) {
    wp_clear_scheduled_hook("ja_postviews_db_day");
}
if (wp_next_scheduled('ja_postviews_db_week')) {
    wp_clear_scheduled_hook("ja_postviews_db_week");
}
if (wp_next_scheduled('ja_postviews_mail_week')) {
    wp_clear_scheduled_hook("ja_postviews_mail_week");
}