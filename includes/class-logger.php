<?php
namespace Wpsmp;

class Logger {

    /**
     * Log messages to a debug file within the plugin dir.
     */
    public static function log( $message ) {
        $log_file = WPSMP_PLUGIN_DIR . 'wpsmp-debug.log';
        $time = current_time( 'mysql' );
        $log_entry = "[{$time}] {$message}" . PHP_EOL;
        file_put_contents( $log_file, $log_entry, FILE_APPEND );
    }
}
