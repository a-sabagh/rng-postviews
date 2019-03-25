<?php

/*
  Plugin Name: rng-postviews
  Description: WordPress plugin that set post view count for each post and reports them in a chart. in a front end, you have a widget and shortcode to show post view count.
  Version: 1.0
  Author: Abolfazl Sabagh
  Author URI: http://asabagh.ir
  License: GPLv2 or later
  Text Domain: rng-postviews
 */
define(RNGJA_FILE, __FILE__);
define(RNGJA_PRU, plugin_basename(__FILE__));
define(RNGJA_PDU, plugin_dir_url(__FILE__));   //http://localhost:8888/rng-plugin/wp-content/plugins/rng-postViews/
define(RNGJA_PRT, basename(__DIR__));          //rng-postviews.php
define(RNGJA_PDP, plugin_dir_path(__FILE__));  //Applications/MAMP/htdocs/rng-plugin/wp-content/plugins/rng-postViews
define(RNGJA_TMP, RNGJA_PDP . "/public/");     // view OR templates directory for public 
define(RNGJA_ADM, RNGJA_PDP . "/admin/");      // view OR templates directory for admin panel

require_once 'includes/class.init.php';
$rngja_init = new rngja_init(1.0, 'rng-postviews');


if (!function_exists("rngja_get_post_viewe_count")) {

    /**
     * return post view count by id
     * @global Object $rngja_postviewes
     * @param Integer $post_id
     * @return boolean
     */
    function rngja_get_post_viewe_count($post_id) {
        global $rngja_postviewes;
        $args = array('post_type' => get_post_type($post_id));
        $is_legal_post_views = $rngja_postviewes->is_legal_post_veiws($args);
        if (!$is_legal_post_views) {
            return false;
        }
        $post_views = $rngja_postviewes->get_postviewes_count($post_id);
        return $post_views;
    }

}