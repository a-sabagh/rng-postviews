<?php
/*
  Plugin Name: RNG_PostViews
  Description: wordpress plugin for post views reporting with email and chart. with great feature like setting page.
  Version: 1.0
  Author: abolfazl sabagh
  Author URI: http://asabagh.ir
  License: GPLv2 or later
  Text Domain: rng-ajaxlike
 */

define(JA_PRU, plugin_basename( __FILE__ ));  
define(JA_PDU, plugin_dir_url(__FILE__));   //http://localhost:8888/rng-plugin/wp-content/plugins/rng-postViews/
define(JA_PRT, basename(__DIR__));          //rng-postviews.php
define(JA_PDP, plugin_dir_path(__FILE__));  //Applications/MAMP/htdocs/rng-plugin/wp-content/plugins/rng-postViews
define(JA_TMP, JA_PDP . "/public/");        // view OR templates directory for public 
define(JA_ADM, JA_PDP . "/admin/");         // view OR templates directory for admin panel

require_once 'includes/class.init.php';
$refresh_init = new ja_init(1.0,'rng-postviews');
