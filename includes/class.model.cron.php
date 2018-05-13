<?php

if (!class_exists("ja_cron_model")) {

    class ja_cron_model {
        
        public function update_db_cron_day($args = array()){            
            global $wpdb;
            $wpdb->query( "START TRANSACTION" );
            $result = array();
            $result[] = update_option("ja_postviews_day_first", 0);
            $result[] = update_option("ja_postviews_day_second", 0);
            $result[] = update_option("ja_postviews_day_third", 0);
            $result[] = update_option("ja_postviews_day_fourth", 0);
            $result[] = update_option("ja_postviews_day_fifth", 0);
            $result[] = update_option("ja_postviews_day_sixth", 0);
            $result[] = update_option("ja_postviews_day_seventh", 0);
            if(in_array(FALSE, $result)){
                $wpdb->query( "ROLLBACK" );
            } else {
                $wpdb->query( "COMMIT" );
            }
            
        }
        
        public function update_db_cron_week(){
            
        }
    }

}