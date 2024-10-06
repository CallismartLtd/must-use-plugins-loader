<?php
/*
 * Plugin Name: CTMU Loader
 * Plugin URI: https://callismart.com.ng/
 * Description: Callismart Tech mu-plugins loader.
 * Author: Callistus Nwachukwu
 * Author URI: https://callismart.com.ng/callistus
 * Version: 0.0.1
 */

defined( 'ABSPATH' ) || exit;

if ( defined( 'CTMU_LOADER' ) ) {
    return;
}

define( 'CTMUL_LOADER', TRUE );
define( 'CTMUL_FILE',  __FILE__ );
define( 'CTMUL_PATH', dirname( CTMUL_FILE ) . '/' );

/**
 * Load all must-use plugins by requiring all .php files of the folders in this directory.
 */
function ctmul_load_mu_plugins() {
    if ( ( ! defined( 'CTMUL_PATH' ) ) || ( ! is_dir( CTMUL_PATH ) ) ) {
        return;
    }

    $plugin_files = ctmul_get_plugins();

    if ( ! empty( $plugin_files ) ) {
        foreach ( $plugin_files as $plugin ) {
            $plugin = CTMUL_PATH . $plugin;
            if ( file_exists( $plugin ) ) {
                require_once $plugin;
            }
        }
    }
}

/**
 * Get all folders in this mu-plugin directory and their main plugin file.
 *
 * @return array Array of main plugin file paths relative to the plugin directory.
 */
function ctmul_get_plugins() {
    $all_files_and_folders = scandir( CTMUL_PATH );
    $plugin_files  = array();
    $unwanted_res  = array( '.', '..', 'load.php' ); // Exclude unwanted entries like current file and dot directories.

    if ( $all_files_and_folders ) {
        foreach ( $all_files_and_folders as $dir_resource ) {
            if ( in_array( $dir_resource, $unwanted_res, true ) ) {
                continue;
            }

            // Check if the resource is a directory.
            if ( is_dir( CTMUL_PATH . $dir_resource ) ) {
                // Main plugin file should match the folder name (e.g., folder/folder.php).
                $plugin_file = trailingslashit( $dir_resource ) . $dir_resource . '.php';

                if ( file_exists( CTMUL_PATH . $plugin_file ) ) {
                    $plugin_files[] = $plugin_file;
                }
            }
        }
    }

    return ! empty( $plugin_files ) ? wp_unslash( $plugin_files ) : array();
}

// Run the function to load all MU plugins.
ctmul_load_mu_plugins();