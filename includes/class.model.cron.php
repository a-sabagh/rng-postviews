<?php

if (!class_exists("ja_cron_model")) {

    class ja_cron_model {
        
        public function update_db_cron_day($args = array()){            
            global $wpdb;
            $wpdb->query( "START TRANSACTION" );
            $result = array();
            $result[] = update_option("ja_postviews_day_first", 0);
            $result[] = update_option("ja_postviews_day_second", current($args));
            $result[] = update_option("ja_postviews_day_third", next($args));
            $result[] = update_option("ja_postviews_day_fourth", next($args));
            $result[] = update_option("ja_postviews_day_fifth", next($args));
            $result[] = update_option("ja_postviews_day_sixth", next($args));
            $result[] = update_option("ja_postviews_day_seventh", next($args));
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
            $result[] = update_option("ja_postviews_week_first", 0);
            $result[] = update_option("ja_postviews_week_second", current($args));
            $result[] = update_option("ja_postviews_week_third", next($args));
            $result[] = update_option("ja_postviews_week_fourth", next($args));
            if(in_array(FALSE, $result)){
                $wpdb->query( "ROLLBACK" );
            }else{
                $wpdb->query( "COMMIT" );
            }
        }
    }

}