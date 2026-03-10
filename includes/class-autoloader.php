<?php
/**
 * Simple PSR-4 Autoloader
 */

spl_autoload_register( function ( $class ) {
    // Project-specific namespace prefix
    $prefix = 'Wpsmp\\';
    
    // Base directory for the namespace prefix
    $base_dir = WPSMP_PLUGIN_DIR . 'includes/';
    
    // Does the class use the namespace prefix?
    $len = strlen( $prefix );
    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        // No, move to the next registered autoloader
        return;
    }
    
    // Get the relative class name
    $relative_class = substr( $class, $len );
    $parts = explode('\\', $relative_class);
    $class_name = array_pop($parts);
    
    // Convert namespace to path
    $file_path = '';
    if (!empty($parts)) {
        $file_path .= strtolower(implode('/', $parts)) . '/';
    }
    
    // Format class file name matching WordPress coding standards
    $file_name = 'class-' . strtolower(str_replace('_', '-', $class_name)) . '.php';
    $file = $base_dir . $file_path . $file_name;
    
    // If the file exists, require it
    if ( file_exists( $file ) ) {
        require $file;
    }
});
