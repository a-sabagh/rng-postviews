<?php

if (!class_exists("ja_cron_model")) {

    class ja_cron_model {

        public function update_db_cron_day($args = array()){
            global $wpdb;
            $wpdb->query( "START TRANSACTION" );
            $result = array();
            $result[] = ($args[0] !== 0)? update_option("ja_postviews_day_first", 0) : TRUE;
            $result[] = ($args[0] !== $args[1])? update_option("ja_postviews_day_second", $args[0]) : TRUE;
            $result[] = ($args[1] !== $args[2])?update_option("ja_postviews_day_third", $args[1]): TRUE;
            $result[] = ($args[2] !== $args[3])?update_option("ja_postviews_day_fourth", $args[2]) : TRUE;
            $result[] = ($args[3] !== $args[4])?update_option("ja_postviews_day_fifth", $args[3]) : TRUE;
            $result[] = ($args[4] !== $args[5])?update_option("ja_postviews_day_sixth", $args[4]) : TRUE;
            $result[] = ($args[5] !== $args[6])? update_option("ja_postviews_day_seventh", $args[5]) : TRUE;
            if(in_array(FALSE, $result)){
                $wpdb->query( "ROLLBACK" );
            } else {
                $wpdb->query( "COMMIT" );
            }
            
        }

        public function update_db_cron_week($args = array()){
            global $wpdb;
            $wpdb->query( "START TRANSACTION" );
            $result = array();
            $result[] = ($args[0] !== 0)?update_option("ja_postviews_week_first", 0): TRUE;
            $result[] = ($args[0] !== $args[1])?update_option("ja_postviews_week_second", $args[0]): TRUE;
            $result[] = ($args[1] !== $args[2])?update_option("ja_postviews_week_third", $args[1]) : TRUE;
            $result[] = ($args[2] !== $args[3])?update_option("ja_postviews_week_fourth", $args[2]): TRUE;
            if(in_array(FALSE, $result)){
                $wpdb->query( "ROLLBACK" );
            }else{
                $wpdb->query( "COMMIT" );
            }
        }
        
    }

}